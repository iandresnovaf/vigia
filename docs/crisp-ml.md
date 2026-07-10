# Metodología CRISP-ML — VigIA

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
- El ciudadano no tiene a quién "preguntarle" sobre el estado ambiental de su municipio en lenguaje natural

### Objetivo del proyecto
Desarrollar un dashboard web que:
1. Consuma datos abiertos de datos.gov.co en tiempo real vía API Socrata SODA
2. Integre lecturas horarias de un dron IoT con sensores ambientales
3. Use inteligencia artificial (LLM) para traducir los datos a lenguaje ciudadano
4. Emita alertas automáticas cuando se detecten condiciones de riesgo
5. Permita conversación natural con un asistente IA que consulta datos reales
6. Prediga tendencias futuras y genere recomendaciones personalizadas

### Criterios de éxito
- El dashboard carga datos reales de datos.gov.co sin intervención manual
- Las alertas del dron se emiten en menos de 60 segundos tras una lectura anómala
- Un ciudadano sin conocimiento técnico puede entender el estado del ambiente en su municipio
- El chat responde correctamente a preguntas sobre dominios distintos (aire, seguridad, incendios, clima)
- La predicción de 7 días se visualiza como línea punteada coherente con la tendencia histórica

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

#### Datos del dron IoT / sensor externo (fuente propia)

El dron o sensor externo publica por API un JSON con estructura:

```json
{
  "ok": true,
  "rows": [
    {
      "municipio": "Pereira",
      "estacion": "DRON-01",
      "fecha_formateada": "2026-07-01 10:00",
      "diametro_aerodinamico": "PM2.5",
      "medicion": 18.4
    }
  ],
  "server_time": "2026-07-01T10:05:00"
}
```

La función `transformarFilasSensor()` en `dron.php` pivota las filas por tipo de medición a formato columnar:

```json
{
  "captured_at": "2026-07-01 10:00",
  "municipio": "Pereira",
  "estacion": "DRON-01",
  "pm25": 18.4,
  "pm10": 32.1,
  "no2": 12.5,
  "ruido_db": 62.3
}
```

La URL del sensor se configura desde la interfaz (⚙️ IA → Sensores & Datos) y se persiste en la tabla `llm_config`.

### Exploración y calidad de datos

**Calidad de los datasets de aire:**
- Rango temporal: 2015–2026 según fuente
- Variables clave: `fecha`, `municipio`, `estacion`, `categoria` (tipo de contaminante), `medicion` (µg/m³)
- Outliers detectados: algunas fuentes reportan valores > 1,000 µg/m³ (errores de sensor). Se aplica filtro `v > 1000 → excluir del gráfico` (datos crudos se conservan en tabla)

**Calidad de los datasets de seguridad:**
- Estructura homogénea entre hurtos/homicidios/lesiones: `fecha_hecho, departamento, municipio, cantidad`
- Cobertura: nacional, desde 2003
- Sin valores nulos críticos en campos clave

**Datos del sensor/dron:**
- Frecuencia: 1 lectura/hora
- Almacenamiento MySQL con `UNIQUE KEY (device_id, captured_at)` para idempotencia
- Durante desarrollo: `cron/sample_dron.json` con datos de Pereira y Dosquebradas

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
// Ejemplo de mapeo (fuente IDEAM, formato long)
'campo_fecha'     => 'med_fecha_inicio',
'campo_valor'     => 'med_concentracion_estandar',
'campo_municipio' => 'municipio',
'campo_categoria' => 'msfl_code',

// Ejemplo de mapeo (SVCA, formato wide)
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

### Sistema multi-agente (Nivel Avanzado)

La arquitectura de IA central es un pipeline de **2 llamadas LLM en cadena** implementado en `public/api/chat.php`:

```
Usuario → /api/chat.php
              │
              ▼ Llamada 1: Agente Clasificador (max_tokens=100)
         { dominio, necesita_datos, parametro }
              │
              ▼ Fetch automático SocrataClient (20 registros)
              │
              ▼ Llamada 2: Agente Especialista (max_tokens=500)
    ┌─────────────────────────────────────────────────────┐
    │  dominio=aire/incendios/clima  → Agente Ambiental   │
    │  dominio=seguridad             → Agente Seguridad   │
    │  dominio=prediccion            → Agente Predictor   │
    │  dominio=simulacion            → Agente Simulador   │
    │  dominio=general               → Agente VigIA       │
    └─────────────────────────────────────────────────────┘
              │
              ▼ { respuesta, agente, datos_usados, dominio }
```

**Prompt del Agente Clasificador:**
```
Eres el Agente Clasificador de VigIA (monitoreo ambiental y seguridad, Colombia).
Clasifica la intención del mensaje ciudadano. Responde SOLO con JSON válido:
{"dominio":"aire|seguridad|incendios|clima|prediccion|simulacion|general",
 "necesita_datos":true|false,"parametro":"pm25|pm10|hurtos|temperatura|..."}
```

**Contexto de historial**: las últimas 4 interacciones se incluyen como texto para mantener continuidad conversacional.

### Componentes LLM en api/llm.php

El endpoint `/api/llm.php` soporta 4 tipos de llamada:

#### tipo=interpretar — NLG ciudadano
```
Sistema: Eres un asistente amigable para ciudadanos colombianos. Analizas datos
ambientales y de seguridad y los explicas sin tecnicismos. Máximo 3 oraciones.

Usuario: Analiza estos datos de [tema] y explícalos a un ciudadano común: [datos JSON]
```

#### tipo=alertar — Detección de anomalías en tiempo real
```
Umbrales: PM2.5 > 150 µg/m³ = posible incendio;
          PM10 > 200 µg/m³ = posible incendio;
          ruido_db > 85 dB = exceso de ruido (límite OMS);
          tipo_comportamiento sospechoso = alerta de seguridad

Respuesta esperada: {"alerta":true|false,"tipo":"incendio|ruido|seguridad|ninguna","mensaje":"..."}
```
Polling cada 60 segundos desde `app-llm.js`. Toast UI con 🔥 🔊 🚨 según el tipo.

#### tipo=predecir — Analítica predictiva ESTADÍSTICA (7 días)

La predicción **no la genera el LLM**: se calcula en `src/Analitica.php` de forma determinística y reproducible.

1. `serieDiaria()` agrega los datos oficiales en una serie diaria numérica (con filtro de outliers).
2. `regresionLineal()` ajusta por mínimos cuadrados → `slope`, `intercept`, **R²**.
3. `pronostico()` extrapola 7 días y calcula la **banda de confianza** (±1.96·σ de los residuos).
4. `backtest()` reserva las últimas 7 observaciones, pronostica ese tramo y calcula **MAE, RMSE y MAPE**.

El LLM (Agente Predictor) **solo narra** los números ya calculados; se le instruye explícitamente
"NO inventes ni cambies cifras". Respuesta:
```
{"metodo":"regresion_lineal",
 "tendencia":"alza|baja|estable",
 "prediccion_7dias":[...7 valores calculados...],
 "intervalo_confianza":{"low":[...],"high":[...]},
 "confianza":"alta|media|baja",
 "metricas":{"mae":..,"rmse":..,"mape":..,"r2":..},
 "narrativa":"explicación del LLM (opcional)",
 "procedencia":{dataset, id, municipio, registros, ultima_fecha}}
```
Los 7 valores + la banda se dibujan en el chart (`borderDash:[5,5]` + relleno). **Funciona sin API key**
(narrativa por plantilla). Se activa con ≥ 5 días de serie.

#### tipo=alertar — Reglas determinísticas (no LLM)
La decisión de alerta la toma `src/Alertas.php` con umbrales auditables (PM2.5>150, PM10>200, ruido>85 dB OMS).
El LLM solo redacta el mensaje ciudadano de una alerta ya disparada. Respuesta incluye
`{valor, umbral, parametro, metodo:"regla_deterministica"}`.

#### tipo=recomendar — Recomendaciones personalizadas
```
Sistema: Eres el Agente Recomendador de VigIA. Genera 3 recomendaciones concretas
para ciudadanos de [municipio], Colombia, basadas en datos actuales de [tema].
Considera grupos vulnerables (niños, adultos mayores) y contexto colombiano.
Responde SOLO JSON: {"recomendaciones":["rec1","rec2","rec3"]}
```
Se muestran en el panel `#llm-recommendations` bajo la interpretación IA, identificado con el nombre del municipio.

### Multi-proveedor LLM

Todos los agentes y tipos de llamada son multi-proveedor. La función `dispatchLLM()` enruta al handler correcto:

| Proveedor | Handler | Formato de request |
|-----------|---------|-------------------|
| Kimi / Moonshot AI | `callLLM()` | OpenAI-compatible |
| OpenAI | `callLLM()` | OpenAI-compatible |
| OpenRouter | `callLLM()` + headers extra | OpenAI-compatible + `HTTP-Referer`, `X-Title` |
| Google Gemini | `callLLM()` | OpenAI-compatible via `/v1beta/openai/` |
| Anthropic Claude | `callAnthropic()` | `/v1/messages`, `x-api-key`, formato propio |
| Personalizado | `callLLM()` | OpenAI-compatible, URL configurable |

La API key se persiste en `llm_config` (MySQL). **Nunca se almacena en archivos del repositorio.**

### Configuración dinámica de sensores

El `SocrataClient` usa un `static $overrideToken` que `datos_abiertos.php` establece desde DB antes de cada consulta, permitiendo que cada instalación use su propio Socrata App Token sin modificar código.

La URL del sensor externo se lee de `llm_config` en cada request de `dron.php`, permitiendo cambiarla sin reiniciar el servidor.

---

## Fase 5 — Evaluación

### APIs de datos.gov.co

Todos los endpoints fueron verificados el **2026-07-01**:

```bash
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

### Chat multi-agente

Evaluado con 5 dominios distintos (Kimi K2 como proveedor de prueba):

| Pregunta de prueba | Agente esperado | Resultado |
|--------------------|----------------|-----------|
| "¿Cómo está el aire en Pereira?" | Ambiental | ✅ Clasificó `dominio=aire`, consultó dataset 53gx-j5pc |
| "¿Cuántos hurtos hubo en Bogotá el año pasado?" | Seguridad | ✅ Clasificó `dominio=seguridad`, consultó 4rxi-8m8d |
| "Predíceme el PM2.5 para la próxima semana" | Predictor | ✅ Clasificó `dominio=prediccion` |
| "¿Qué pasaría si queman más caña?" | Simulador | ✅ Clasificó `dominio=simulacion` |
| "¿Qué es VigIA?" | VigIA | ✅ Clasificó `dominio=general`, `necesita_datos=false` |

### Predicción de tendencias — validación estadística (backtesting)

La predicción se valida con **backtesting**: se reservan las últimas 7 observaciones, se pronostican y
se comparan con lo real. Métricas reportadas en cada respuesta (`src/Analitica.php::backtest`):

| Métrica | Significado |
|---------|-------------|
| **MAE** | Error absoluto medio (unidades del parámetro) |
| **RMSE** | Raíz del error cuadrático medio (penaliza errores grandes) |
| **MAPE** | Error porcentual absoluto medio (%) — comparable entre municipios |
| **R²** | Bondad de ajuste de la regresión lineal |

Pruebas realizadas:
- Serie sintética perfectamente lineal → `prediccion_7dias` exacta, R²=1, MAE=RMSE=MAPE=0 ✅
- Serie con ruido → banda de confianza se ensancha, MAPE≈11.6%, R²≈0.80 ✅
- Datos reales Pereira (PM, 170 días) → tendencia "estable", MAPE≈9.3%, R²≈0.00 (serie plana) ✅
- La predicción y las alertas **funcionan sin API key** (analítica determinística) ✅
- Banda de confianza (95%) + línea punteada aparecen en el chart ✅

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

### Configuración inicial (primera vez)

1. Abrir `http://localhost:8000`
2. Hacer clic en **⚙️ IA**
3. Tab **🤖 Asistente IA**: seleccionar proveedor y modelo, ingresar API key → Guardar
4. Tab **🛰️ Sensores & Datos**: (opcional) ingresar URL del dron/sensor y Socrata App Token → Guardar
5. El sistema queda listo: interpretación, recomendaciones, predicción y chat activos

### Arquitectura de despliegue productivo (escalabilidad)

```
Internet → Nginx/Apache → PHP-FPM 8.2 → MySQL (replica R/O para lecturas)
                              ↕
                     Cron horario (fetch_dron.php)
                              ↕
               Sensores IoT / drones (múltiples, URL configurable)
                              ↕
                    LLM API (cualquier proveedor) — llamadas bajo demanda
```

- **Multi-dron**: `device_id` diferente por dron; sensor URL configurable desde UI
- **Multi-municipio**: cada dron reporta `municipio`; la UI filtra automáticamente
- **Multi-proveedor LLM**: el administrador cambia el proveedor desde la UI sin tocar código
- **Cache de datos abiertos**: tabla `datos_abiertos_cache` disponible para reducir llamadas a Socrata
- **Fase 2 — Visión por computador**: `ai-service/app.py` (FastAPI + YOLO/MediaPipe) recibe frames del dron y clasifica comportamientos; PHP orquesta via `AiClient.php`

---

## Resumen de decisiones técnicas

| Decisión | Alternativa descartada | Razón |
|----------|----------------------|-------|
| PHP 8.2 como backend | Python/Django | Stack solicitado; PHP es dominante en hosting colombiano |
| Socrata SODA API | Descarga CSV | API permite filtros en tiempo real sin almacenamiento local |
| Multi-proveedor LLM (6 opciones) | Solo Kimi | Flexibilidad para equipos con distintos presupuestos y proveedores |
| Pipeline 2-llamadas (Clasificador → Especialista) | Prompt único con instrucciones condicionales | Separación de responsabilidades; mejor calidad de clasificación; prompts más cortos y precisos por agente |
| JSON estructurado en predecir/recomendar | Texto libre | Parseable programáticamente; integración directa con Chart.js y DOM |
| Polling 60s para alertas | WebSockets | Simplicidad; el dron publica cada hora (no requiere tiempo real estricto) |
| Socrata token via DB (override estático) | Modificar Config.php | Permite cambio en caliente desde UI sin reiniciar el servidor |
| MySQL + PDO | SQLite | Soporte multi-dron y consultas concurrentes |
| Formato long normalizado | Raw por fuente | Uniformidad en el frontend independiente de la fuente |

---

*Documento actualizado: 2026-07-02 · VigIA — Concurso Datos al Ecosistema 2026: IA para Colombia*
