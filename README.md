# VigIA — IA para Colombia 🇨🇴

> *Inteligencia Artificial que VIGILA, PROTEGE y CUIDA tu entorno.*

**Concurso Datos al Ecosistema 2026 · IA para Colombia**  
Categorías: **Sostenibilidad y Medio Ambiente** · **Innovación Social (Seguridad Ciudadana)**

---

## ¿Qué es VigIA?

Dashboard web interactivo que integra **datos abiertos de datos.gov.co** con lecturas en tiempo real de un **dron de monitoreo ambiental** y un **sistema multi-agente de IA** para interpretar datos, predecir tendencias, emitir alertas y responder preguntas en lenguaje natural a ciudadanos colombianos.

### El problema que resuelve

Los ciudadanos colombianos no tienen acceso fácil a información ambiental y de seguridad en tiempo real y en lenguaje comprensible. Los datos abiertos existen pero están fragmentados en portales técnicos. Cuando hay un incendio forestal, niveles peligrosos de contaminación o un pico de inseguridad, la ciudadanía tarda en enterarse.

### La solución

Un único punto de acceso que:
- Consolida **9 fuentes de datos abiertos** de calidad del aire, incendios, clima y seguridad
- Integra lecturas horarias de un **dron IoT** con sensores PM2.5, PM10, NO₂, O₃ y ruido
- Despliega un **sistema multi-agente** con 5 agentes especializados (Ambiental, Seguridad, Predictor, Simulador, VigIA)
- Permite **chat conversacional** en lenguaje natural: el ciudadano pregunta y los agentes responden con datos reales
- **Predice tendencias** a 7 días con línea punteada en el gráfico y análisis de confianza
- Genera **recomendaciones personalizadas** por municipio y tema
- Emite **alertas automáticas en tiempo real**: 🔥 incendio, 🔊 ruido excesivo, 🚨 seguridad

---

## Nivel de participación

| Criterio del concurso | Implementación en VigIA |
|----------------------|------------------------|
| IA generativa / chat conversacional | Chat 💬 con historial, enruta a 5 agentes especializados |
| Sistema multi-agente | Agente Clasificador → Especialista (2 llamadas LLM en cadena) |
| Analítica predictiva avanzada | `tipo=predecir` → 7 valores JSON + línea punteada en Chart.js |
| Detección de anomalías | Agente Clasificador detecta contexto de riesgo; umbrales en alertas |
| Modelos de simulación | Agente Simulador responde "¿qué pasaría si…?" |
| Recomendaciones personalizadas | `tipo=recomendar` → 3 items por municipio y tema |
| Integración de datos abiertos + IoT | 9 datasets Socrata + dron externo por URL configurable |
| Arquitectura escalable | N fuentes = N entradas en Config.php; multi-dron por `device_id` |

---

## Retos del concurso abordados

| # | Reto | Nivel |
|---|------|-------|
| 2 | Seguridad Ciudadana y Justicia | Avanzado |
| 6 | Desarrollo Sostenible y Medio Ambiente | Avanzado |

---

## Datasets utilizados (datos.gov.co)

Todos los conjuntos de datos son consultados en tiempo real via **API Socrata SODA** (`resource/{ID}.json`).

### 🌫️ Calidad del Aire

| Fuente | ID | Cobertura |
|--------|----|-----------|
| [Calidad del Aire — CVC Risaralda](https://www.datos.gov.co/Ambiente-y-Desarrollo-Sostenible/Calidad-del-Aire/53gx-j5pc) | `53gx-j5pc` | Pereira, Dosquebradas, La Virginia, Santa Rosa de Cabal |
| [Calidad del Aire en Colombia — IDEAM](https://www.datos.gov.co/Ambiente-y-Desarrollo-Sostenible/Calidad-del-Aire-en-Colombia/g4t8-zkc3) | `g4t8-zkc3` | Nacional (PM10, PM2.5, SO₂, NO₂, O₃) |
| [SVCA — PM10 y PM2.5 (Caldas/Risaralda)](https://www.datos.gov.co/Ambiente-y-Desarrollo-Sostenible/Sistema-de-Informacion-sobre-Calidad-del-Aire-PM10/yspz-pxxn) | `yspz-pxxn` | Caldas y Risaralda |
| [Calidad del Aire — Duitama, Boyacá](https://www.datos.gov.co/Ambiente-y-Desarrollo-Sostenible/Calidad-del-Aire-Municipio-de-Duitama/aghd-ge2f) | `aghd-ge2f` | Duitama |

### 🔥 Incendios Forestales

| Fuente | ID | Cobertura |
|--------|----|-----------|
| [Incendios Cobertura Vegetal — CORPOBOYACÁ](https://www.datos.gov.co/Ambiente-y-Desarrollo-Sostenible/Incendios-Cobertura-Vegetal/ryr5-rs2a) | `ryr5-rs2a` | Boyacá |

### 🌦️ Clima / Normales Climatológicas

| Fuente | ID | Cobertura |
|--------|----|-----------|
| [Normales Climatológicas — IDEAM](https://www.datos.gov.co/Ambiente-y-Desarrollo-Sostenible/Normales-Climatol-gicas/nsz2-kzcq) | `nsz2-kzcq` | Nacional (1961–2010) |

### 🛡️ Seguridad Ciudadana (SIEDCO — Policía Nacional)

| Fuente | ID | Cobertura |
|--------|----|-----------|
| [Hurto a Personas](https://www.datos.gov.co/Seguridad-y-Defensa/HURTO-PERSONAS/4rxi-8m8d/data) | `4rxi-8m8d` | Nacional, hasta 2026 |
| [Homicidios](https://www.datos.gov.co/Seguridad-y-Defensa/HOMICIDIO/m8fd-ahd9) | `m8fd-ahd9` | Nacional, desde 2003 |
| [Lesiones Personales](https://www.datos.gov.co/Seguridad-y-Defensa/LESIONES-PERSONALES/jr6v-i33g) | `jr6v-i33g` | Nacional, desde 2003 |

---

## Arquitectura del sistema

```
┌──────────────────────────────────────────────────────────────────────┐
│                        FUENTES DE DATOS                              │
│                                                                      │
│  datos.gov.co (API Socrata SODA)        Dron / Sensor IoT            │
│  9 datasets: aire / incendios /         URL configurable por usuario │
│  clima / seguridad                      PM2.5, PM10, NO₂, O₃, ruido │
└────────────────────┬──────────────────────────┬──────────────────────┘
                     │                          │
                     ▼                          ▼
┌──────────────────────────────────────────────────────────────────────┐
│                       BACKEND PHP 8.2                                │
│                                                                      │
│  SocrataClient.php  ─ consulta APIs con filtros SoQL + App Token     │
│  DronRepository.php ─ upsert y lectura de MySQL                      │
│  Config.php         ─ mapeo de datasets y campos                    │
│                                                                      │
│  APIs REST internas:                                                 │
│  /api/datos_abiertos.php   /api/dron.php   /api/comparar.php        │
│  /api/llm.php              /api/config.php  /api/chat.php           │
└────────────────────────────────┬─────────────────────────────────────┘
                                 │
                                 ▼
┌──────────────────────────────────────────────────────────────────────┐
│              SISTEMA MULTI-AGENTE (api/chat.php + api/llm.php)       │
│                                                                      │
│  Ciudadano → /api/chat.php                                           │
│                  │                                                   │
│                  ▼ LLM Call 1: Agente Clasificador                   │
│             { dominio, necesita_datos, parametro }                   │
│                  │                                                   │
│                  ▼ Fetch automático datos.gov.co / dron              │
│                  │                                                   │
│                  ▼ LLM Call 2: Agente Especialista                   │
│    ┌─────────────┬──────────────┬───────────────┬──────────────────┐ │
│    │  Ambiental  │  Seguridad   │   Predictor   │  Simulador/VigIA │ │
│    └─────────────┴──────────────┴───────────────┴──────────────────┘ │
│                                                                      │
│  /api/llm.php también soporta:                                       │
│    tipo=interpretar  → NLG 3 oraciones                               │
│    tipo=alertar      → JSON {"alerta", "tipo", "mensaje"}            │
│    tipo=predecir     → {"tendencia", "prediccion_7dias"[7], ...}     │
│    tipo=recomendar   → {"recomendaciones"[3]}                        │
└────────────────────────────────┬─────────────────────────────────────┘
                                 │
                                 ▼
┌──────────────────────────────────────────────────────────────────────┐
│              MYSQL / MARIADB 11.4                                    │
│  dron_lecturas · dron_eventos_seguridad · llm_config                 │
└────────────────────────────────┬─────────────────────────────────────┘
                                 │
                                 ▼
┌──────────────────────────────────────────────────────────────────────┐
│                FRONTEND (PHP + jQuery + Chart.js)                    │
│                                                                      │
│  Menú: 🌿 Ambiente  ·  🛡️ Seguridad                                 │
│  5 temas: Aire · Ruido · Incendios · Clima · Seguridad               │
│  3 vistas: Datos Abiertos · Dron · Comparación                       │
│                                                                      │
│  🤖 Panel IA: interpretación + predicción 7d (línea punteada)        │
│  💡 Recomendaciones personalizadas por municipio                     │
│  💬 Chat flotante con historial (5 agentes especializados)           │
│  🔔 Alertas en tiempo real (polling 60 s): 🔥 🔊 🚨                 │
└──────────────────────────────────────────────────────────────────────┘
```

---

## Tecnologías utilizadas

| Capa | Tecnología |
|------|-----------|
| Backend | PHP 8.2 |
| Base de datos | MySQL / MariaDB 11.4 |
| Frontend | HTML5 + jQuery 3.7 + Chart.js 4.4 |
| API de datos | Socrata SODA (datos.gov.co) |
| IA / LLM | Multi-proveedor: Kimi, OpenAI, OpenRouter, Google Gemini, Anthropic Claude, Custom |
| Hardware | Dron IoT (sensores PM2.5, PM10, NO₂, O₃, ruido, GPS) |

### Proveedores LLM soportados

| Proveedor | URL base | Modelos de ejemplo |
|-----------|----------|--------------------|
| Kimi / Moonshot AI | `api.moonshot.cn/v1` | `kimi-k2-0711`, `moonshot-v1-8k` |
| OpenAI | `api.openai.com/v1` | `gpt-4o-mini`, `gpt-4o` |
| OpenRouter | `openrouter.ai/api/v1` | `gemini-2.0-flash:free`, `llama-3.3-70b:free` |
| Google Gemini | `generativelanguage.googleapis.com/v1beta/openai` | `gemini-2.0-flash`, `gemini-1.5-pro` |
| Anthropic Claude | `api.anthropic.com/v1/messages` | `claude-haiku-4-5`, `claude-sonnet-4-6` |
| Personalizado | URL configurable | Cualquier API OpenAI-compatible |

---

## Metodología CRISP-ML

Ver [`docs/crisp-ml.md`](docs/crisp-ml.md) para la documentación detallada.

**Resumen de fases:**
1. **Comprensión del negocio** — ciudadanos sin acceso a datos ambientales en tiempo real
2. **Comprensión de los datos** — exploración de 9 APIs Socrata + telemetría del dron
3. **Preparación** — normalización long/wide, filtro de outliers, integración multi-fuente
4. **Modelado** — Sistema multi-agente (Clasificador → Especialista); NLG; predicción 7 días; recomendaciones personalizadas; alertas por umbrales
5. **Evaluación** — APIs verificadas en vivo (2026-07-01); alertas probadas; chat probado con 5 dominios
6. **Despliegue** — dashboard web accesible; dron como sensor IoT en campo; sensor URL configurable

---

## Cómo ejecutar el proyecto

### Requisitos
- PHP 8.2+ con extensiones: `pdo_mysql`, `curl`, `mbstring`, `openssl`
- MySQL / MariaDB 11.4+
- Git

### Instalación

```bash
git clone https://github.com/iandresnovaf/vigia.git
cd vigia
```

#### 1. Base de datos

```sql
mysql -u root -p < sql/schema.sql
```

#### 2. Configuración

`src/Config.php` ya incluye valores por defecto para desarrollo local. Ajustar si es necesario:

```php
public const DB = [
    'host' => '127.0.0.1',
    'port' => '3306',
    'name' => 'dashboard_entorno',
    'user' => 'root',
    'pass' => '',
];
```

#### 3. Datos de prueba del dron

```bash
php cron/fetch_dron.php
```

Carga `cron/sample_dron.json` en la BD con lecturas de Pereira y Dosquebradas.

#### 4. Servidor web

```bash
php -S localhost:8000 -t public
```

Abrir `http://localhost:8000` en el navegador.

#### 5. Configurar el asistente IA

Hacer clic en **⚙️ IA** en el topbar:

**Tab "🤖 Asistente IA":**
- Seleccionar proveedor (Kimi, OpenAI, OpenRouter, Gemini, Claude, Custom)
- Seleccionar modelo
- Ingresar API key → Guardar

Una vez configurado:
- El panel de interpretación IA aparece al cargar datos
- Las recomendaciones personalizadas se muestran automáticamente
- La predicción de 7 días se superpone al gráfico (si hay ≥ 10 registros)
- El chat 💬 (esquina inferior derecha) está activo

**Tab "🛰️ Sensores & Datos":**
- **URL del sensor/dron**: endpoint JSON propio (`{ok, rows: [{municipio, estacion, fecha_formateada, diametro_aerodinamico, medicion}]}`)
- **Socrata App Token**: token gratuito en data.socrata.com para mayor límite de llamadas a datos.gov.co

#### 6. Chat con VigIA

Hacer clic en **💬** (esquina inferior derecha). Preguntas de ejemplo:
- *"¿Cómo está la calidad del aire en Pereira esta semana?"*
- *"¿Cuál es la tendencia de hurtos en Bogotá?"*
- *"¿Qué pasaría si la temperatura sube 5°C en mi municipio?"* → Agente Simulador
- *"Predíceme el PM2.5 para los próximos 7 días"* → Agente Predictor

---

## Impacto y escalabilidad

- **Impacto ambiental**: monitoreo continuo de calidad del aire en zonas con poca cobertura estatal
- **Impacto en seguridad**: alertas predictivas basadas en patrones históricos del SIEDCO
- **Chat ciudadano**: cualquier persona puede hacer preguntas en lenguaje natural y recibir respuestas con datos reales
- **Predicción proactiva**: anticipa situaciones de riesgo ambiental con 7 días de horizonte
- **Escalabilidad**: añadir un nuevo dataset requiere solo una entrada en `src/Config.php`; la arquitectura soporta N fuentes sin cambiar el frontend
- **Replicabilidad**: cualquier municipio puede conectar su propio dron o sensor IoT configurando la URL en la interfaz

---

## Estructura del repositorio

```
vigia/
├── public/                      # Webroot (PHP built-in server / Apache)
│   ├── index.php                # UI principal + chat panel
│   ├── api/                     # Endpoints REST internos
│   │   ├── datos_abiertos.php   # Proxy a datos.gov.co (Socrata SODA)
│   │   ├── dron.php             # Lecturas del dron / sensor externo
│   │   ├── comparar.php         # Series alineadas dron vs datos abiertos
│   │   ├── llm.php              # Proxy LLM: interpretar/alertar/predecir/recomendar
│   │   ├── chat.php             # Chat multi-agente: Clasificador → Especialista
│   │   └── config.php           # Configuración del LLM + sensor URL + Socrata token
│   └── assets/
│       ├── js/app.js            # UI principal (jQuery + Chart.js + mostrarPrediccion)
│       ├── js/app-llm.js        # Módulo IA (interpretación + recomendaciones + predicción)
│       ├── js/app-chat.js       # Chat flotante multi-agente
│       └── css/styles.css       # Estilos (incluyendo chat FAB y panel)
├── src/
│   ├── Config.php               # Configuración central (datasets + DB)
│   ├── Db.php                   # Conexión PDO singleton
│   ├── SocrataClient.php        # Cliente HTTP para datos.gov.co (con token override)
│   ├── DronRepository.php       # Repositorio de lecturas del dron
│   └── AiClient.php             # Cliente del microservicio de visión (fase 2)
├── cron/
│   ├── fetch_dron.php           # Cron horario: descarga JSON del dron → MySQL
│   └── sample_dron.json         # Datos de ejemplo (Pereira, Dosquebradas)
├── sql/
│   └── schema.sql               # Esquema MySQL completo
├── docs/
│   ├── crisp-ml.md              # Metodología CRISP-ML detallada
│   └── datasets.md              # Catálogo de todos los datasets evaluados
├── config/
│   └── config.example.php       # Plantilla de configuración sin secretos
└── ai-service/
    └── app.py                   # (Fase 2) Microservicio FastAPI para visión por computador
```

---

## Publicación en datos.gov.co

Este proyecto está registrado en el portal de usos de datos abiertos:  
👉 [herramientas.datos.gov.co/usos](https://herramientas.datos.gov.co/usos)

---

## Licencia

MIT License — libre uso, modificación y distribución con atribución.

---

## Equipo

**VigIA** — desarrollado para el **Concurso Datos al Ecosistema 2026: IA para Colombia**  
Organizado por el **Ministerio de TIC — datos.gov.co**  
🔖 `DRONES` · `INTELIGENCIA ARTIFICIAL` · `DATOS ABIERTOS` · `BIENESTAR Y SALUD`
