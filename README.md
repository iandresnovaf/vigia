<div align="center">

# VigIA — IA para Colombia 🇨🇴

> *Inteligencia Artificial que VIGILA, PROTEGE y CUIDA tu entorno.*

![Concurso](https://img.shields.io/badge/Concurso-Datos%20al%20Ecosistema%202026-1a2e5c)
![Equipo](https://img.shields.io/badge/Equipo-80-1db87f)
![Stack](https://img.shields.io/badge/PHP%208.2%20·%20MySQL%20·%20jQuery%20·%20Chart.js-informational)
![IA](https://img.shields.io/badge/IA-multiagente%20%2B%20anal%C3%ADtica%20estad%C3%ADstica-1db87f)
![Datos](https://img.shields.io/badge/datos.gov.co-11%20datasets-2563ab)
![Licencia](https://img.shields.io/badge/licencia-MIT-green)

**Concurso Datos al Ecosistema 2026 · IA para Colombia**
Categorías: **Sostenibilidad y Medio Ambiente** · **Innovación Social (Seguridad Ciudadana)**

**Equipo 80** — Mariana Martinez · Nestor Santos · Andres Nova

</div>

---

## 🔗 Accesos rápidos

| Entregable | Enlace |
|-----------|--------|
| 🚀 **Demo en vivo** (dashboard) | ⬜ _(pegar URL de la demo pública)_ |
| 🎥 **Video de presentación** | ⬜ _(pegar URL del video, 2–3 min)_ |
| 🛰️ **Modelo de visión** (detección de eventos) | [xbmpkywc5iisxlzxmwvfsc.streamlit.app](https://xbmpkywc5iisxlzxmwvfsc.streamlit.app/) |
| 📊 **Publicación en datos.gov.co** | [herramientas.datos.gov.co/usos](https://herramientas.datos.gov.co/usos) _(registro en trámite)_ |
| 💻 **Repositorio** | [github.com/iandresnovaf/vigia](https://github.com/iandresnovaf/vigia) |

> 🧑‍⚖️ **¿Eres jurado?** Ve directo a [**Modo jurado: evalúa en 5 minutos**](#modo-jurado-evalúa-en-5-minutos).

---

## Tabla de contenido

1. [Problema y solución](#problema-y-solución)
2. [Demo y capturas](#demo-y-capturas)
3. [Cómo cumple la rúbrica del concurso](#cómo-cumple-la-rúbrica-del-concurso)
4. [Datos abiertos usados](#datos-abiertos-usados)
5. [Arquitectura y tecnologías](#arquitectura-y-tecnologías)
6. [Analítica y rigor técnico](#analítica-y-rigor-técnico)
7. [Sensor IoT y visión por computador](#sensor-iot-y-visión-por-computador)
8. [Impacto y escalabilidad](#impacto-y-escalabilidad)
9. [Ética y gobernanza de IA](#ética-y-gobernanza-de-ia)
10. [Modo jurado: evalúa en 5 minutos](#modo-jurado-evalúa-en-5-minutos)
11. [Cómo ejecutar](#cómo-ejecutar)
12. [Estructura del repositorio](#estructura-del-repositorio)
13. [Equipo](#equipo) · [Licencia](#licencia)

---

## Problema y solución

**VigIA** es un dashboard web que integra **datos abiertos de datos.gov.co** con lecturas en tiempo real de un **sensor IoT de campo** y un **sistema multi-agente de IA** para interpretar datos, predecir tendencias, emitir alertas y responder preguntas en lenguaje natural a ciudadanos colombianos.

**El problema.** Los ciudadanos colombianos no tienen acceso fácil a información ambiental y de seguridad en tiempo real y en lenguaje comprensible. Los datos abiertos existen pero están fragmentados en portales técnicos. Cuando hay un incendio forestal, niveles peligrosos de contaminación o un pico de inseguridad, la ciudadanía tarda en enterarse.

**La solución.** Un único punto de acceso que:
- Consolida **11 datasets de datos.gov.co** (4 aire, 1 incendios, 1 clima, 5 seguridad) vía API Socrata SODA
- Integra un **sensor IoT real desplegado** (ESP8266 + MQ2) que envía mediciones de campo por API
- **Predice tendencias a 7 días con analítica estadística** (regresión lineal + intervalo de confianza + **backtesting con métricas MAE/RMSE/MAPE**) — reproducible, no generada por IA
- Despliega un **sistema multi-agente** con 5 agentes especializados (Ambiental, Seguridad, Predictor, Simulador, VigIA)
- Permite **chat conversacional** en lenguaje natural con **trazabilidad**: cada respuesta muestra dataset, ID, municipio, registros y fecha
- Cruza **detección de hurto por visión (modelo TSN)** con la estadística SIEDCO por municipio
- Genera **recomendaciones personalizadas** por municipio y tema
- Emite **alertas por reglas determinísticas auditables**: 🔥 incendio, 🔊 ruido excesivo, 🚨 seguridad

> **Gobernanza de IA:** la analítica es estadística (el LLM solo explica, no inventa cifras), las alertas
> son reglas determinísticas, y cada respuesta es verificable. Ver [ética y gobernanza](docs/etica-y-gobernanza.md).

---

## Demo y capturas

> 📸 _Añade tus capturas en `docs/img/` y enlázalas aquí (reemplaza los placeholders)._

| Vista | Captura |
|-------|---------|
| Dashboard — Calidad del aire + predicción 7 días | `docs/img/dashboard-aire.png` ⬜ |
| Chat multi-agente con trazabilidad | `docs/img/chat.png` ⬜ |
| Seguridad en tiempo real — visión × SIEDCO | `docs/img/seguridad.png` ⬜ |
| Modo jurado (guía interactiva) | `docs/img/modo-jurado.png` ⬜ |

```markdown
<!-- Ejemplo de inserción una vez tengas la imagen -->
![Dashboard VigIA](docs/img/dashboard-aire.png)
```

---

## Cómo cumple la rúbrica del concurso

| Criterio (peso) | Cómo lo cumple VigIA |
|-----------------|----------------------|
| **Innovación y creatividad (15)** | Une datos oficiales + sensor IoT real + visión por computador + IA conversacional multi-agente en una sola experiencia ciudadana |
| **Uso de datos abiertos (20)** | 11 datasets de datos.gov.co consultados **en vivo** por API Socrata SODA; normalización long/wide; tabla dataset→valor |
| **Análisis y rigor técnico (15)** | Predicción por **regresión lineal + backtesting (MAE/RMSE/MAPE)** en `src/Analitica.php`; alertas por **reglas determinísticas** en `src/Alertas.php` — el LLM solo narra |
| **Tecnologías emergentes / IA (20)** | Sistema **multi-agente** (Clasificador→Especialista), predicción, recomendaciones, **visión TSN** (hurto) cruzada con SIEDCO, multi-proveedor LLM |
| **Impacto y escalabilidad (20)** | Casos territoriales con indicadores; N datasets por configuración; sensor IoT replicable; funciona **sin API key** |

**Retos abordados**

| # | Reto | Nivel |
|---|------|-------|
| 2 | Seguridad Ciudadana y Justicia | Avanzado |
| 6 | Desarrollo Sostenible y Medio Ambiente | Avanzado |

---

## Datos abiertos usados

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
| [Hurto a Comercio](https://www.datos.gov.co/Seguridad-y-Defensa/HURTO-A-COMERCIO/7i2x-h5vp) | `7i2x-h5vp` | Nacional |
| [Hurto a Residencias](https://www.datos.gov.co/Seguridad-y-Defensa/HURTO-A-RESIDENCIAS/7mn7-vzqp) | `7mn7-vzqp` | Nacional |
| [Homicidios](https://www.datos.gov.co/Seguridad-y-Defensa/HOMICIDIO/m8fd-ahd9) | `m8fd-ahd9` | Nacional, desde 2003 |
| [Lesiones Personales](https://www.datos.gov.co/Seguridad-y-Defensa/LESIONES-PERSONALES/jr6v-i33g) | `jr6v-i33g` | Nacional, desde 2003 |

**Total: 11 datasets de datos.gov.co** (4 aire · 1 incendios · 1 clima · 5 seguridad) + sensor IoT real para ruido/aire de campo.

### Dataset → problema público → uso → valor

| Dataset | Problema público | Uso en VigIA | Valor generado |
|---------|------------------|--------------|----------------|
| Calidad del Aire (CVC/IDEAM) | Ciudadanos no saben si el aire es peligroso | Serie diaria + predicción 7 días + alerta | Prevención de exposición para grupos vulnerables |
| Incendios CORPOBOYACÁ | Riesgo forestal territorial poco visible | Área afectada por tipo + cruce con sensor | Alerta temprana de quema/incendio |
| Normales Climatológicas IDEAM | Falta de contexto climático local | Referencia mensual por parámetro | Interpretación estacional de las mediciones |
| Hurto Personas/Comercio/Residencias SIEDCO | Datos de seguridad difíciles de consultar | Tendencia por municipio + **cruce con visión** | Contexto para prevención y priorización |
| Homicidios / Lesiones SIEDCO | Panorama de violencia disperso | Serie por municipio + agente Seguridad | Respuestas ciudadanas verificables |

Catálogo completo (incluidos datasets descartados y por qué): [`docs/datasets.md`](docs/datasets.md).

---

## Arquitectura y tecnologías

```
┌──────────────────────────────────────────────────────────────────────┐
│                        FUENTES DE DATOS                              │
│                                                                      │
│  datos.gov.co (API Socrata SODA)        Dron / Sensor IoT            │
│  11 datasets: aire / incendios /        URL configurable por usuario │
│  clima / seguridad                      PM2.5, PM10, NO₂, O₃, ruido │
└────────────────────┬──────────────────────────┬──────────────────────┘
                     │                          │
                     ▼                          ▼
┌──────────────────────────────────────────────────────────────────────┐
│                       BACKEND PHP 8.2                                │
│  SocrataClient.php · DronRepository.php · Analitica.php · Alertas.php │
│  CruceHurto.php · Config.php                                          │
│  APIs: datos_abiertos · dron · comparar · llm · chat · evento · config│
└────────────────────────────────┬─────────────────────────────────────┘
                                 │
                                 ▼
┌──────────────────────────────────────────────────────────────────────┐
│              SISTEMA MULTI-AGENTE (api/chat.php + api/llm.php)       │
│  Ciudadano → Agente Clasificador → { dominio, necesita_datos }       │
│            → fetch datos.gov.co/dron → Agente Especialista           │
│    ┌─────────────┬──────────────┬───────────────┬──────────────────┐ │
│    │  Ambiental  │  Seguridad   │   Predictor   │  Simulador/VigIA │ │
│    └─────────────┴──────────────┴───────────────┴──────────────────┘ │
│  api/llm.php: interpretar · alertar(regla) · predecir(estadística) · │
│               recomendar   —  procedencia en cada respuesta          │
└────────────────────────────────┬─────────────────────────────────────┘
                                 │
                                 ▼
┌──────────────────────────────────────────────────────────────────────┐
│  MYSQL / MARIADB 11.4  ·  dron_lecturas · dron_eventos_seguridad ·    │
│                           llm_config                                  │
└────────────────────────────────┬─────────────────────────────────────┘
                                 ▼
┌──────────────────────────────────────────────────────────────────────┐
│                FRONTEND (PHP + jQuery + Chart.js)                    │
│  🤖 Interpretación + 📈 predicción 7d (banda de confianza + métricas) │
│  💡 Recomendaciones · 💬 Chat multi-agente · 🛰️ Seguridad tiempo real │
│  🎥 Modelo de visión · 🧑‍⚖️ Modo jurado · 🔔 Alertas determinísticas    │
└──────────────────────────────────────────────────────────────────────┘
```

| Capa | Tecnología |
|------|-----------|
| Backend | PHP 8.2 |
| Base de datos | MySQL / MariaDB 11.4 |
| Frontend | HTML5 + jQuery 3.7 + Chart.js 4.4 |
| API de datos | Socrata SODA (datos.gov.co) |
| IA / LLM | Multi-proveedor: Kimi, OpenAI, OpenRouter, Google Gemini, Anthropic Claude, Custom |
| Visión por computador | Modelo TSN (MMAction2) para detección de eventos de seguridad |
| Hardware | Sensor IoT real (ESP8266 + MQ2); arquitectura lista para dron/sensor por API |

**Proveedores LLM soportados**

| Proveedor | URL base | Modelos de ejemplo |
|-----------|----------|--------------------|
| Kimi / Moonshot AI | `api.moonshot.cn/v1` | `kimi-k2-0711`, `moonshot-v1-8k` |
| OpenAI | `api.openai.com/v1` | `gpt-4o-mini`, `gpt-4o` |
| OpenRouter | `openrouter.ai/api/v1` | `gemini-2.0-flash:free`, `llama-3.3-70b:free` |
| Google Gemini | `generativelanguage.googleapis.com/v1beta/openai` | `gemini-2.0-flash`, `gemini-1.5-pro` |
| Anthropic Claude | `api.anthropic.com/v1/messages` | `claude-haiku-4-5`, `claude-sonnet-4-6` |
| Personalizado | URL configurable | Cualquier API OpenAI-compatible |

---

## Analítica y rigor técnico

La predicción **no la genera el LLM**: se calcula de forma determinística y reproducible en
[`src/Analitica.php`](src/Analitica.php).

1. **Serie diaria** a partir de los datos oficiales (con filtro de outliers).
2. **Regresión lineal** por mínimos cuadrados → tendencia + R².
3. **Pronóstico a 7 días** con **banda de confianza** (±1.96·σ de los residuos).
4. **Backtesting**: reserva las últimas 7 observaciones y calcula **MAE, RMSE y MAPE**.

El LLM (Agente Predictor) **solo explica** los números ya calculados. Las **alertas** son reglas
determinísticas auditables ([`src/Alertas.php`](src/Alertas.php): PM2.5>150, PM10>200, ruido>85 dB OMS).
Todo esto **funciona aunque no haya API key de LLM**.

Metodología completa: [`docs/crisp-ml.md`](docs/crisp-ml.md) (CRISP-ML(Q), 6 fases).

---

## Sensor IoT y visión por computador

- **Sensor IoT real desplegado:** un ESP8266 + MQ2 envía mediciones de campo por API (piloto real).
  La arquitectura acepta cualquier dron/sensor vía endpoint JSON configurable en la UI.
- **Visión por computador × datos abiertos:** un modelo **TSN (MMAction2)** clasifica eventos de
  hurto/riesgo en video; [`src/CruceHurto.php`](src/CruceHurto.php) mapea la clase detectada a su
  modalidad SIEDCO (personas / comercio / residencias) y la **cruza con la estadística oficial** del
  municipio. Ingesta por webhook (`api/evento.php`) o microservicio (`ai-service/app.py`).

---

## Impacto y escalabilidad

- **Impacto ambiental**: monitoreo continuo de calidad del aire en zonas con poca cobertura estatal
- **Impacto en seguridad**: alertas predictivas basadas en patrones históricos del SIEDCO
- **Chat ciudadano**: cualquier persona pregunta en lenguaje natural y recibe respuestas con datos reales
- **Predicción proactiva**: anticipa riesgo ambiental con 7 días de horizonte
- **Escalabilidad**: un nuevo dataset = una entrada en `src/Config.php`; N fuentes sin cambiar el frontend
- **Replicabilidad**: cualquier municipio conecta su propio dron/sensor configurando la URL

### Casos territoriales (impacto medible)

**Caso 1 — Pereira / Dosquebradas (aire).** PM2.5/PM10 incomprensibles → VigIA traduce, predice 7 días
(regresión + backtesting) y alerta. Indicadores: días de serie analizados, MAPE, tiempo de consulta de minutos a segundos.

**Caso 2 — Bogotá (hurtos).** SIEDCO difícil de explorar → el chat responde "¿tendencia de hurtos en
Bogotá?" con cifras y procedencia; la visión detecta la modalidad y la cruza. Indicadores: eventos cruzados, tendencia 90 días, modalidades cubiertas.

**Caso 3 — Boyacá (incendios).** Riesgo forestal territorial → incendios históricos CORPOBOYACÁ + sensor
de campo. Indicadores: hectáreas por tipo, municipios cubiertos.

### Indicadores de impacto (medibles)

| Indicador | Cómo se mide | Estado |
|-----------|--------------|--------|
| Datasets abiertos integrados | Entradas en `Config::DATASETS` | **11** |
| Municipios soportados | Filtro por municipio en cada fuente | Nacional (según cobertura) |
| Tiempo de consulta ciudadana | Antes (explorar Socrata) vs. después (1 clic) | de minutos a **segundos** |
| Calidad de la predicción | MAPE del backtesting a 7 días | reportado en cada predicción |
| Modalidades de hurto cruzadas visión×SIEDCO | Personas, comercio, residencias | **3** |
| Independencia del LLM | Predicción/alertas sin API key | ✅ analítica determinística |

---

## Ética y gobernanza de IA

Sin reconocimiento facial · sin identificación individual · solo análisis agregado · alertas de **apoyo**
(no decisión automática) · minimización de datos · trazabilidad y revisión humana.
Documento completo: [`docs/etica-y-gobernanza.md`](docs/etica-y-gobernanza.md).

---

## Modo jurado: evalúa en 5 minutos

En el dashboard, botón **🧑‍⚖️ Modo jurado** (guía interactiva que ejecuta cada paso). Manualmente:

1. Tema **Aire** → municipio **Pereira** → *Aplicar*.
2. Observa la **procedencia** (dataset, ID, registros, última fecha) bajo la interpretación.
3. Revisa la **predicción 7 días**: línea punteada + banda de confianza + **métricas R²/MAE/RMSE/MAPE**.
4. Pregunta al chat: *"¿Cómo está el aire en Pereira?"*
5. Cambia a **Seguridad** → pregunta *"¿tendencia de hurtos en Bogotá?"*.
6. Mira el panel **Seguridad en Tiempo Real**: evento de visión + **cruce con SIEDCO**.
7. Abre el **Modelo de Visión** (detección de eventos en video).

> La predicción y las alertas funcionan **aunque no haya API key de LLM** (la analítica es estadística).

---

## Cómo ejecutar

### Requisitos
- PHP 8.2+ con extensiones: `pdo_mysql`, `curl`, `mbstring`, `openssl`
- MySQL / MariaDB 11.4+
- Git

### Instalación

```bash
git clone https://github.com/iandresnovaf/vigia.git
cd vigia
```

**1. Base de datos**
```sql
mysql -u root -p < sql/schema.sql
```

**2. Configuración** — `src/Config.php` trae valores por defecto para desarrollo local (ajustar si hace falta):
```php
public const DB = ['host' => '127.0.0.1', 'port' => '3306', 'name' => 'dashboard_entorno', 'user' => 'root', 'pass' => ''];
```

**3. Datos de ejemplo del sensor**
```bash
php cron/fetch_dron.php   # carga cron/sample_dron.json (Pereira, Dosquebradas)
```

**4. Servidor web**
```bash
php -S localhost:8000 -t public   # abrir http://localhost:8000
```

**5. Configurar el asistente IA (opcional)** — botón **⚙️ IA**:
- Tab **🤖 Asistente IA**: proveedor (Kimi/OpenAI/OpenRouter/Gemini/Claude/Custom) + modelo + API key.
- Tab **🛰️ Sensores & Datos**: URL de tu sensor/dron y Socrata App Token.

> La predicción, las alertas y el cruce con SIEDCO funcionan **sin API key**. El chat y las
> recomendaciones sí requieren un proveedor LLM configurado.

**Preguntas de ejemplo para el chat 💬**
- *"¿Cómo está la calidad del aire en Pereira esta semana?"*
- *"¿Cuál es la tendencia de hurtos en Bogotá?"*
- *"¿Qué pasaría si la temperatura sube 5°C en mi municipio?"* → Agente Simulador
- *"Predíceme el PM2.5 para los próximos 7 días"* → Agente Predictor

---

## Estructura del repositorio

```
vigia/
├── public/                      # Webroot
│   ├── index.php                # UI principal (dashboard + chat + paneles)
│   ├── api/                     # Endpoints REST internos
│   │   ├── datos_abiertos.php   # Proxy a datos.gov.co (Socrata SODA)
│   │   ├── dron.php             # Lecturas del sensor/dron
│   │   ├── comparar.php         # Series alineadas sensor vs datos abiertos
│   │   ├── llm.php              # interpretar · alertar · predecir · recomendar
│   │   ├── chat.php             # Chat multi-agente (Clasificador → Especialista)
│   │   ├── evento.php           # Ingesta de eventos de visión + cruce SIEDCO
│   │   └── config.php           # Config LLM + sensor URL + Socrata token
│   └── assets/js/               # app.js · app-llm.js · app-chat.js · app-seguridad.js
│                                # · app-videos.js · app-jurado.js
├── src/
│   ├── Config.php               # Datasets + CV_CLASS_MAP + DB
│   ├── Analitica.php            # Regresión, pronóstico 7d, backtesting (MAE/RMSE/MAPE)
│   ├── Alertas.php              # Alertas por reglas determinísticas
│   ├── CruceHurto.php           # Cruce visión (TSN) × SIEDCO por municipio
│   ├── SocrataClient.php        # Cliente datos.gov.co (con token override)
│   ├── DronRepository.php       # Persistencia de lecturas/eventos
│   ├── AiClient.php             # Cliente del microservicio de visión
│   └── Db.php                   # Conexión PDO
├── cron/                        # fetch_dron.php + sample_dron.json
├── sql/schema.sql               # Esquema MySQL
├── ai-service/                  # Microservicio FastAPI (wrapper del modelo TSN)
├── docs/                        # crisp-ml.md · datasets.md · etica-y-gobernanza.md · img/
└── config/config.example.php    # Plantilla sin secretos
```

---

## Equipo

**Equipo 80**
- **Mariana Martinez**
- **Nestor Santos**
- **Andres Nova**

Desarrollado para el **Concurso Datos al Ecosistema 2026: IA para Colombia** — Organizado por el
**Ministerio de TIC · datos.gov.co**.
🔖 `DRONES` · `INTELIGENCIA ARTIFICIAL` · `DATOS ABIERTOS` · `BIENESTAR Y SALUD`

## Licencia

MIT License — libre uso, modificación y distribución con atribución.
