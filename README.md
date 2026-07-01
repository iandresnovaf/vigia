# VigIA — IA para Colombia 🇨🇴

> *Inteligencia Artificial que VIGILA, PROTEGE y CUIDA tu entorno.*

**Concurso Datos al Ecosistema 2026 · IA para Colombia**  
Categorías: **Sostenibilidad y Medio Ambiente** · **Innovación Social (Seguridad Ciudadana)**

---

## ¿Qué es VigIA?

Dashboard web interactivo que integra **datos abiertos de datos.gov.co** con lecturas en tiempo real de un **dron de monitoreo ambiental** equipado con sensores de calidad del aire y ruido, e **inteligencia artificial (Kimi / Moonshot AI)** para interpretar los datos y emitir alertas tempranas a ciudadanos colombianos.

### El problema que resuelve

Los ciudadanos colombianos no tienen acceso fácil a información ambiental y de seguridad en tiempo real y en lenguaje comprensible. Los datos abiertos existen pero están fragmentados en portales técnicos. Cuando hay un incendio forestal, niveles peligrosos de contaminación o un pico de inseguridad, la ciudadanía tarda en enterarse.

### La solución

Un único punto de acceso que:
- Consolida **9 fuentes de datos abiertos** de calidad del aire, incendios, clima y seguridad
- Integra lecturas horarias de un **dron IoT** con sensores PM2.5, PM10, NO₂, O₃ y ruido
- Usa un **LLM (Kimi / Moonshot AI)** para traducir los datos a lenguaje ciudadano
- Emite **alertas automáticas en tiempo real**: 🔥 incendio, 🔊 ruido excesivo, 🚨 seguridad

---

## Retos del concurso abordados

| # | Reto | Nivel |
|---|------|-------|
| 2 | Seguridad Ciudadana y Justicia | Intermedio |
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
┌─────────────────────────────────────────────────────────────────┐
│                    FUENTES DE DATOS                             │
│                                                                 │
│  datos.gov.co (API Socrata SODA)      Dron IoT (JSON horario)  │
│  9 datasets abiertos                  PM2.5, PM10, NO2, O3,    │
│  aire / incendios / clima /           ruido_dB, batería, GPS   │
│  seguridad                                                      │
└──────────────────┬───────────────────────────┬─────────────────┘
                   │                           │
                   ▼                           ▼
┌─────────────────────────────────────────────────────────────────┐
│                    BACKEND PHP 8.2                              │
│                                                                 │
│  SocrataClient.php  ─ consulta APIs con filtros SoQL            │
│  DronRepository.php ─ upsert y lectura de MySQL                 │
│  Config.php         ─ mapeo de datasets y campos               │
│  AiClient.php       ─ (fase 2) orquesta microservicio CV       │
│                                                                 │
│  APIs REST internas:                                            │
│  /api/datos_abiertos.php  /api/dron.php                        │
│  /api/comparar.php        /api/llm.php  /api/config.php        │
└──────────────────────────────┬──────────────────────────────────┘
                               │
                               ▼
┌─────────────────────────────────────────────────────────────────┐
│              MYSQL / MARIADB 11.4                               │
│  dron_lecturas · dron_eventos_seguridad · llm_config            │
└──────────────────────────────┬──────────────────────────────────┘
                               │
                               ▼
┌─────────────────────────────────────────────────────────────────┐
│                   FRONTEND (PHP + jQuery + Chart.js)            │
│                                                                 │
│  Menú desplegable:  🌿 Ambiente  ·  🛡️ Seguridad               │
│  5 temas: Aire · Ruido · Incendios · Clima · Seguridad          │
│  3 vistas por tema: Datos Abiertos · Dron · Comparación         │
│                                                                 │
│  ┌────────────────────────────────────────────────────┐        │
│  │  🤖 Asistente IA — Kimi / Moonshot AI              │        │
│  │  • Interpretación automática en lenguaje ciudadano  │        │
│  │  • Alertas en tiempo real (polling 60 s):           │        │
│  │    🔥 PM2.5 > 150 → posible incendio                │        │
│  │    🔊 ruido_dB > 85 → exceso de ruido (OMS)         │        │
│  │    🚨 comportamiento sospechoso → seguridad          │        │
│  └────────────────────────────────────────────────────┘        │
└─────────────────────────────────────────────────────────────────┘
```

---

## Tecnologías utilizadas

| Capa | Tecnología |
|------|-----------|
| Backend | PHP 8.2 |
| Base de datos | MySQL / MariaDB 11.4 |
| Frontend | HTML5 + jQuery 3.7 + Chart.js 4.4 |
| API de datos | Socrata SODA (datos.gov.co) |
| IA / LLM | Kimi K2 / Moonshot AI (OpenAI-compatible) |
| Hardware | Dron IoT (sensores PM2.5, PM10, NO₂, O₃, ruido, GPS) |

---

## Metodología CRISP-ML

Ver [`docs/crisp-ml.md`](docs/crisp-ml.md) para la documentación detallada.

**Resumen de fases:**
1. **Comprensión del negocio** — ciudadanos sin acceso a datos ambientales en tiempo real
2. **Comprensión de los datos** — exploración de 9 APIs Socrata + telemetría del dron
3. **Preparación** — normalización long/wide, filtro de outliers, integración multi-fuente
4. **Modelado** — LLM para NLG; umbrales para detección de anomalías; visualizaciones
5. **Evaluación** — APIs verificadas en vivo (2026-07-01); alertas probadas con datos de muestra
6. **Despliegue** — dashboard web accesible; dron como sensor IoT en campo

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

`src/Config.php` ya incluye valores por defecto para desarrollo local (MySQL en localhost, sin contraseña). Ajustar si es necesario:

```php
// src/Config.php
public const DB = [
    'host' => '127.0.0.1',
    'port' => '3306',
    'name' => 'dashboard_entorno',
    'user' => 'root',
    'pass' => '',          // ajustar
];
public const DRON_URL = ''; // URL JSON del dron (vacío usa sample_dron.json)
```

#### 3. Datos de prueba del dron

```bash
php cron/fetch_dron.php
```

Esto carga `cron/sample_dron.json` en la BD con lecturas de Pereira y Dosquebradas.

#### 4. Servidor web

```bash
php -S localhost:8000 -t public
```

Abrir `http://localhost:8000` en el navegador.

#### 5. Configurar el asistente IA (opcional)

1. Hacer clic en **⚙️ IA** en el topbar del dashboard
2. Seleccionar proveedor: **Kimi (Moonshot AI)**
3. Ingresar tu API key de [api.moonshot.cn](https://api.moonshot.cn)
4. Guardar → las interpretaciones y alertas quedarán activas

---

## Impacto y escalabilidad

- **Impacto ambiental**: monitoreo continuo de calidad del aire en zonas con poca cobertura estatal
- **Impacto en seguridad**: alertas predictivas basadas en patrones históricos del SIEDCO
- **Escalabilidad**: añadir un nuevo dataset requiere solo una entrada en `src/Config.php`; la arquitectura soporta N fuentes sin cambiar el frontend
- **Replicabilidad**: cualquier municipio puede conectar su propio dron y ver sus datos junto con los abiertos nacionales
- **Hardware abierto**: el dron es un prototipo propio; la API de entrada es un simple JSON horario, reemplazable por cualquier sensor IoT

---

## Estructura del repositorio

```
vigia/
├── public/                    # Webroot (PHP built-in server / Apache)
│   ├── index.php              # UI principal
│   ├── api/                   # Endpoints REST internos
│   │   ├── datos_abiertos.php # Proxy a datos.gov.co (Socrata SODA)
│   │   ├── dron.php           # Lecturas del dron desde MySQL
│   │   ├── comparar.php       # Series alineadas dron vs datos abiertos
│   │   ├── llm.php            # Proxy LLM (interpretación + alertas)
│   │   └── config.php         # Configuración del LLM
│   └── assets/
│       ├── js/app.js          # Lógica principal (jQuery + Chart.js)
│       ├── js/app-llm.js      # Módulo IA (modal, interpretación, alertas)
│       └── css/styles.css     # Estilos
├── src/
│   ├── Config.php             # Configuración central (datasets + DB)
│   ├── Db.php                 # Conexión PDO singleton
│   ├── SocrataClient.php      # Cliente HTTP para datos.gov.co
│   ├── DronRepository.php     # Repositorio de lecturas del dron
│   └── AiClient.php           # Cliente del microservicio de visión (fase 2)
├── cron/
│   ├── fetch_dron.php         # Cron horario: descarga JSON del dron → MySQL
│   └── sample_dron.json       # Datos de ejemplo (Pereira, Dosquebradas)
├── sql/
│   └── schema.sql             # Esquema MySQL completo
├── docs/
│   ├── crisp-ml.md            # Metodología CRISP-ML detallada
│   └── datasets.md            # Catálogo de todos los datasets evaluados
├── config/
│   └── config.example.php     # Plantilla de configuración sin secretos
└── ai-service/
    └── app.py                 # (Fase 2) Microservicio FastAPI para visión por computador
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
