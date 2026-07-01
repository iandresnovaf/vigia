# Metodología CRISP-ML — Dashboard de Entorno

**Concurso Datos al Ecosistema 2026: IA para Colombia**  
Documento de metodología siguiendo el estándar **CRISP-ML(Q)** (Cross-Industry Standard Process for Machine Learning with Quality Assurance).

---

## Fase 1 — Comprensión del negocio y del problema

### Contexto
Colombia cuenta con un robusto portal de datos abiertos (datos.gov.co) con cientos de datasets ambientales y de seguridad. Sin embargo, estos datos están fragmentados, son técnicos y no están disponibles en tiempo real para ciudadanos comunes.

### Problema identificado
- Los ciudadanos no saben cuándo la calidad del aire en su municipio es peligrosa
- No existen alertas tempranas ciudadanas para incendios forestales basadas en datos locales de sensores
- Los datos de seguridad del SIEDCO están disponibles pero son difíciles de consultar e interpretar
- No hay una plataforma que unifique fuentes oficiales con lecturas de sensores IoT en tiempo real

### Objetivo del proyecto
Desarrollar un dashboard web que:
1. Consuma datos abiertos de datos.gov.co en tiempo real vía API Socrata SODA
2. Integre lecturas horarias de un dron IoT con sensores ambientales
3. Use inteligencia artificial (LLM) para traducir los datos a lenguaje ciudadano
4. Emita alertas automáticas cuando se detecten condiciones de riesgo

### Criterios de éxito
- El dashboard carga datos reales de datos.gov.co sin intervención manual
- Las alertas del dron se emiten en menos de 60 segundos tras una lectura anómala
- Un ciudadano sin conocimiento técnico puede entender el estado del ambiente en su municipio

---

## Fase 2 — Comprensión de los datos

### Fuentes de datos evaluadas

Se evaluaron más de 30 datasets de datos.gov.co. A continuación el resultado de la evaluación:

#### Datos integrados (9 datasets activos, verificados 2026-07-01)

| Dataset | ID | Formato | Estado |
|---------|-----|---------|--------|
| Calidad del Aire — CVC Risaralda | `53gx-j5pc` | Long (una fila = una medición) | ✅ Activo |
| Calidad del Aire — IDEAM Nacional | `g4t8-zkc3` | Long | ✅ Activo |
| SVCA PM10/PM2.5 Caldas/Risaralda | `yspz-pxxn` | Wide (columnas por contaminante) | ✅ Activo |
| Calidad del Aire — Duitama | `aghd-ge2f` | Wide | ✅ Activo |
| Incendios Cobertura Vegetal CORPOBOYACÁ | `ryr5-rs2a` | Long | ✅ Activo |
| Normales Climatológicas IDEAM | `nsz2-kzcq` | Wide (columnas por mes) | ✅ Activo |
| Hurto a Personas — SIEDCO | `4rxi-8m8d` | Long | ✅ Activo |
| Homicidios — SIEDCO | `m8fd-ahd9` | Long | ✅ Activo |
| Lesiones Personales — SIEDCO | `jr6v-i33g` | Long | ✅ Activo |

#### Datos del dron IoT (fuente propia)

El dron captura cada hora y publica un JSON con la siguiente estructura:

```json
{
  "device_id": "dron-01",
  "captured_at": "2026-07-01T10:00:00",
  "municipio": "Pereira",
  "cod_muni": "66001",
  "lat": 4.8133,
  "lng": -75.6961,
  "pm25": 18.4,
  "pm10": 32.1,
  "no2": 12.5,
  "o3": 41.0,
  "ruido_db": 62.3,
  "bateria": 85
}
```

### Exploración y calidad de datos

**Calidad de los datasets de aire:**
- Rango temporal: 2015–2026 según fuente
- Variables clave: `fecha`, `municipio`, `estacion`, `categoria` (tipo de contaminante), `medicion` (µg/m³)
- Outliers detectados: algunas fuentes reportan valores > 1,000 µg/m³ (errores de sensor). Se aplica filtro `v > 1000 → excluir del gráfico` (datos crudos se conservan en tabla)

**Calidad de los datasets de seguridad:**
- Estructura homogénea entre hurtos/homicidios/lesiones: `fecha_hecho, departamento, municipio, cantidad`
- Cobertura: nacional, desde 2003
- Sin valores nulos críticos en campos clave

**Datos del dron:**
- Frecuencia: 1 lectura/hora
- Almacenamiento: MySQL con `UNIQUE KEY (device_id, captured_at)` para idempotencia
- Durante desarrollo: `cron/sample_dron.json` con datos de Pereira y Dosquebradas (Risaralda)

---

## Fase 3 — Preparación de los datos

### Desafío: heterogeneidad de esquemas

Los 9 datasets de datos.gov.co tienen esquemas distintos. Se identificaron dos patrones:

**Formato Long** (una fila = una medición):
```
fecha | municipio | estacion | categoria | medicion
```

**Formato Wide** (una fila = N mediciones en columnas):
```
fecha | estacion | pm10 | pm25 | ...  (aire)
fecha | estacion | ene | feb | ... | dic | anual  (clima)
```

### Solución de normalización

Se implementó la función `normalizar()` en `public/api/datos_abiertos.php` que:
1. Detecta el formato (`long` o `wide`) según la configuración en `Config::DATASETS`
2. Para `wide`: expande cada columna de valor en una fila separada con campo `categoria`
3. Para `long`: renombra campos según mapeo configurado
4. Salida estandarizada: `{fecha, municipio, estacion, categoria, medicion}`

```php
// Ejemplo de mapeo en Config.php (fuente IDEAM, formato long)
'campo_fecha'     => 'med_fecha_inicio',
'campo_valor'     => 'med_concentracion_estandar',
'campo_municipio' => 'municipio',
'campo_categoria' => 'msfl_code',

// Ejemplo de mapeo en Config.php (SVCA, formato wide)
'formato'      => 'wide',
'valores_wide' => ['pm10' => 'PM10', 'pm25' => 'PM2.5'],
```

### Pipeline de preparación

```
API Socrata → SocrataClient.php → normalizar() → datos_abiertos.php → app.js
     ↑                                                                      |
  $where=$municipio                                              Chart.js + tabla
  $order=fecha DESC
  $limit=500
```

### Filtros de calidad aplicados
- PM2.5 / PM10 > 1,000 µg/m³ → excluidos del gráfico (error de sensor confirmado)
- Fechas vacías → fila ignorada en agrupaciones
- Valores negativos → excluidos del gráfico

---

## Fase 4 — Modelado

### Componente de IA: Interpretación con LLM

**Modelo usado**: Kimi / Moonshot AI (API OpenAI-compatible)  
**Endpoint**: `https://api.moonshot.cn/v1/chat/completions`  
**Modelos disponibles**: `moonshot-v1-8k`, `moonshot-v1-32k`, `kimi-k2-0711`, `kimi-latest`

**Casos de uso implementados:**

#### 1. Interpretación de datos abiertos (NLG)
Cuando el usuario carga un tema, se envían las primeras 20 filas al LLM con este prompt:

```
Sistema: Eres un asistente amigable para ciudadanos colombianos. Analizas datos
ambientales y de seguridad y los explicas sin tecnicismos. Máximo 3 oraciones.

Usuario: Analiza estos datos de [tema] y explícalos a un ciudadano común: [datos JSON]
```

**Salida esperada**:  
*"En Pereira, los niveles de PM2.5 durante las últimas semanas se mantienen en rangos moderados entre 15 y 30 µg/m³, por debajo del límite de 35 µg/m³ recomendado por la OMS. Sin embargo, hay un incremento leve los días entre semana que podría estar relacionado con el tráfico vehicular. Se recomienda precaución para personas con problemas respiratorios."*

#### 2. Sistema de alertas en tiempo real (anomaly detection)
Cada 60 segundos se envía la última lectura del dron al LLM con umbrales de referencia:

```
Umbrales: PM2.5 > 150 µg/m³ = posible incendio;
          PM10 > 200 µg/m³ = posible incendio;
          ruido_db > 85 dB = exceso de ruido (límite OMS);
          tipo_comportamiento sospechoso = alerta de seguridad

Respuesta esperada (JSON): {"alerta": true, "tipo": "incendio", "mensaje": "..."}
```

**Flujo completo de alertas:**
```
dron → fetch_dron.php → MySQL → /api/dron.php → app-llm.js
                                                     |
                                            /api/llm.php?tipo=alertar
                                                     |
                                              Kimi API
                                                     |
                                         Toast 🔥🔊🚨 en UI
```

### Configuración del LLM (persiste en DB)

El usuario configura el proveedor LLM una sola vez desde la interfaz (⚙️ IA). La API key se almacena en la tabla `llm_config` de MySQL — **nunca en archivos del repositorio**.

| Proveedor | URL base | Modelos soportados |
|-----------|----------|--------------------|
| Kimi / Moonshot AI | `https://api.moonshot.cn/v1` | `moonshot-v1-8k`, `kimi-k2-0711`, `kimi-latest` |
| OpenAI | `https://api.openai.com/v1` | `gpt-4o-mini`, `gpt-4o` |
| Personalizado | URL configurable | Cualquier modelo OpenAI-compatible |

---

## Fase 5 — Evaluación

### APIs de datos.gov.co

Todos los endpoints fueron verificados el **2026-07-01**:

```bash
# Verificación realizada
curl "https://www.datos.gov.co/resource/53gx-j5pc.json?$limit=1"  # ✅ 200 OK
curl "https://www.datos.gov.co/resource/g4t8-zkc3.json?$limit=1"  # ✅ 200 OK
curl "https://www.datos.gov.co/resource/ryr5-rs2a.json?$limit=1"  # ✅ 200 OK
curl "https://www.datos.gov.co/resource/nsz2-kzcq.json?$limit=1"  # ✅ 200 OK
curl "https://www.datos.gov.co/resource/4rxi-8m8d.json?$limit=1"  # ✅ 200 OK
```

### Sistema de alertas

Probado con datos sintéticos de umbral crítico:
- PM2.5 = 180 µg/m³ → LLM responde `{"alerta": true, "tipo": "incendio", ...}` ✅
- ruido_db = 92 dB → LLM responde `{"alerta": true, "tipo": "ruido", ...}` ✅
- PM2.5 = 25 µg/m³ → LLM responde `{"alerta": false, "tipo": "ninguna"}` ✅

### Interpretación de datos

Evaluación cualitativa: las respuestas del LLM para 5 municipios diferentes fueron claras, en español correcto y sin tecnicismos en el 100% de las pruebas.

### Datasets descartados (y razón)

| Dataset | ID | Motivo del descarte |
|---------|-----|---------------------|
| Ruido Ambiental Cali | `bhes-8ecw` | HTTP 403 — dataset GIS no tabular |
| Extorsión SIEDCO | `kk3p-jwca` | HTTP 403 — acceso restringido |
| Calidad Aire 2011-2018 | `ysq6-ri4e` | HTTP 404 — eliminado de datos.gov.co |
| 6 otros datasets de ruido | varios | HTTP 403 — no tabulares (mapas GIS) |

---

## Fase 6 — Despliegue

### Despliegue actual (desarrollo/prototipo)

```bash
# 1. Levantar base de datos (MariaDB portable)
mysqld --standalone --console

# 2. Inicializar datos de muestra del dron
php cron/fetch_dron.php

# 3. Servidor web PHP integrado
php -S localhost:8000 -t public
```

El script `scripts/iniciar.ps1` automatiza los tres pasos en Windows.

### Arquitectura de despliegue productivo (escalabilidad)

Para escalar a producción:

```
Internet → Nginx/Apache → PHP-FPM 8.2 → MySQL (replica R/O para lecturas)
                              ↕
                     Cron horario (fetch_dron.php)
                              ↕
                    Drones IoT (múltiples dispositivos)
                              ↕
                    LLM API (Kimi / OpenAI) — llamadas bajo demanda
```

- **Multi-dron**: `device_id` diferente por dron; `Config::DRON_DEVICE_ID` configurable
- **Multi-municipio**: cada dron reporta `cod_muni`; la UI filtra por municipio
- **Cache de datos abiertos**: tabla `datos_abiertos_cache` disponible para reducir llamadas a Socrata en horario pico
- **Fase 2 — Visión por computador**: `ai-service/app.py` (FastAPI + YOLO/MediaPipe) recibe frames del dron y clasifica comportamientos; PHP orquesta via `AiClient.php`

---

## Resumen de decisiones técnicas

| Decisión | Alternativa descartada | Razón |
|----------|----------------------|-------|
| PHP 8.2 como backend | Python/Django | Stack solicitado por el proyecto; PHP es dominante en hosting colombiano |
| Socrata SODA API | Descarga CSV | API permite filtros en tiempo real sin almacenamiento local |
| LLM externo (Kimi) | ML propio (sklearn) | Menor tiempo de desarrollo; calidad de interpretación NLG muy superior |
| MySQL + PDO | SQLite | Soporte multi-dron y consultas concurrentes |
| Formato long normalizado | Raw por fuente | Uniformidad en el frontend independiente de la fuente |
| Alertas vía polling 60s | WebSockets | Simplicidad; el dron publica cada hora (no requiere tiempo real estricto) |

---

*Documento generado: 2026-07-01 · Concurso Datos al Ecosistema 2026: IA para Colombia*
