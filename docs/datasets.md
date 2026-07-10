# Datasets utilizados — VigIA

Todos los datasets provienen de **[datos.gov.co](https://www.datos.gov.co/)** via API Socrata SODA.  
Endpoint base: `https://www.datos.gov.co/resource/{ID}.json`  
Verificación: 2026-07-01 (seguridad ampliada 2026-07-09).  
**Total integrado: 11 datasets** (4 aire · 1 incendios · 1 clima · 5 seguridad) + sensor IoT real.

---

## Calidad del Aire

| # | Nombre | ID | Enlace | Cobertura | Nota |
|---|--------|----|--------|-----------|------|
| 1 | **Calidad del Aire — CVC Risaralda** | `53gx-j5pc` | [Ver dataset](https://www.datos.gov.co/Ambiente-y-Desarrollo-Sostenible/Calidad-del-Aire/53gx-j5pc) | Pereira, Dosquebradas, La Virginia, Santa Rosa de Cabal | Datos hasta 2023. Formato largo: `fecha, municipio, estacion, diametro_aerodinamico, medicion`. |
| 2 | **Calidad del Aire en Colombia — IDEAM** | `g4t8-zkc3` | [Ver dataset](https://www.datos.gov.co/Ambiente-y-Desarrollo-Sostenible/Calidad-del-Aire-en-Colombia/g4t8-zkc3) | Nacional | Desde 2020. Incluye PM10, PM2.5, SO2, NO2, O3, CO y humedad. Campos clave: `med_fecha_inicio, municipio, nombre_est, msfl_code, med_concentracion_estandar`. |
| 3 | **SVCA — PM10 y PM2.5** | `yspz-pxxn` | [Ver dataset](https://www.datos.gov.co/Ambiente-y-Desarrollo-Sostenible/Sistema-de-Informacion-sobre-Calidad-del-Aire-PM10/yspz-pxxn) | Caldas y Risaralda (estaciones SVCA) | Datos recientes (2025). Formato ancho: `fecha_lectura, estacion, pm10, pm25`. Sin campo municipio. |
| 4 | **Calidad del Aire — Duitama (Boyacá)** | `aghd-ge2f` | [Ver dataset](https://www.datos.gov.co/Ambiente-y-Desarrollo-Sostenible/Calidad-del-Aire-Municipio-de-Duitama/aghd-ge2f) | Duitama | Formato ancho: `fecha, nombre_equipo, pm10, pm2_5, co, co2, humedad_relativa, temperatura`. |

### Descartados — Aire

| Dataset | ID | Motivo |
|---------|----|--------|
| Datos de Calidad del Aire 2011-2018 | `ysq6-ri4e` | HTTP 404 — eliminado de datos.gov.co |
| Reporte Calidad de Aire AMB (Bucaramanga) | `58ct-h586` | HTTP 200 pero formato ancho complejo (una columna por estación × contaminante). No integrado. |
| Calidad del Aire Colombia Promedio Anual | `kekd-7v7h` | HTTP 200 pero datos anuales agregados, útiles solo como referencia histórica. No integrado. |
| SVCA Vigilancia Piedecuesta (x2) | `kh7q-whyx`, `qski-u769` | HTTP 200 pero esquema diferente, solapamiento con `yspz-pxxn`. No integrado. |
| Contaminantes Palmira 2008-2020 | `5q44-66qf` | HTTP 200 pero datos anuales con columnas renombradas. No integrado. |
| Monitoreo Calidad Aire Magdalena | `dgnf-6h7v` | HTTP 200 pero esquema gravimétrico complejo (no estándar). No integrado. |
| Mediciones Calidad Aire La Candelaria | `nah8-k5zm` | HTTP 200 pero nombres de columna con caracteres especiales y truncados. No integrado. |

---

## Calidad del Ruido

> **Sin API tabular disponible.** Todos los datasets de ruido en datos.gov.co están publicados como archivos GIS / mapas, no como tablas consultables por Socrata SODA (devuelven HTTP 403 "no row or column access to non-tabular tables").

| Dataset | ID | Estado |
|---------|----|--------|
| Monitoreo Ruido Ambiental Cali | `bhes-8ecw` | HTTP 403 — no tabular |
| (Otros 6 datasets de ruido probados) | varios | HTTP 403 — no tabular |

**Fuente de ruido en el dashboard:** sensor del dron (columna `ruido_db` en MySQL tabla `dron_lecturas`).

---

## Seguridad / Análisis de Entorno

Todos son del sistema **SIEDCO (Policía Nacional de Colombia)**. Estructura homogénea: `fecha_hecho, cod_depto, departamento, cod_muni, municipio, cantidad`.

| # | Nombre | ID | Enlace | Cobertura | Nota |
|---|--------|----|--------|-----------|------|
| 1 | **Hurto a Personas — SIEDCO** | `4rxi-8m8d` | [Ver dataset](https://www.datos.gov.co/Seguridad-y-Defensa/HURTO-PERSONAS/4rxi-8m8d/data) | Nacional | Actualizado a 2026. Dataset principal de seguridad. |
| 2 | **Hurto a Comercio — SIEDCO** | `7i2x-h5vp` | [Ver dataset](https://www.datos.gov.co/Seguridad-y-Defensa/HURTO-A-COMERCIO/7i2x-h5vp) | Nacional | Cruce con clase CV *Shoplifting*. |
| 3 | **Hurto a Residencias — SIEDCO** | `7mn7-vzqp` | [Ver dataset](https://www.datos.gov.co/Seguridad-y-Defensa/HURTO-A-RESIDENCIAS/7mn7-vzqp) | Nacional | Cruce con clase CV *Burglary*. |
| 4 | **Homicidios — SIEDCO** | `m8fd-ahd9` | [Ver dataset](https://www.datos.gov.co/Seguridad-y-Defensa/HOMICIDIO/m8fd-ahd9) | Nacional | Desde 2003. Incluye campos de arma, modalidad y caracterización. |
| 5 | **Lesiones Personales — SIEDCO** | `jr6v-i33g` | [Ver dataset](https://www.datos.gov.co/Seguridad-y-Defensa/LESIONES-PERSONALES/jr6v-i33g) | Nacional | Desde 2003. |

> **Integración visión × datos abiertos:** el modelo de visión (TSN) clasifica eventos de hurto y
> `src/CruceHurto.php` los mapea a la modalidad SIEDCO correspondiente (personas/comercio/residencias)
> para contextualizar la detección en vivo con la estadística oficial del municipio.

### Descartados — Seguridad

| Dataset | ID | Motivo |
|---------|----|--------|
| Extorsión — SIEDCO | `kk3p-jwca` | HTTP 403 — acceso restringido en datos.gov.co |
| Homicidios Accidente de Tránsito (Policía) | `ha6j-pa2r` | HTTP 200 pero campo municipio es `municipio` sin `cod_muni`; solapamiento temático. No integrado. |

---

## Incendios Forestales

| # | Nombre | ID | Enlace | Cobertura | Nota |
|---|--------|----|--------|-----------|------|
| 1 | **Incendios Cobertura Vegetal — CORPOBOYACÁ** | `ryr5-rs2a` | [Ver dataset](https://www.datos.gov.co/Ambiente-y-Desarrollo-Sostenible/Incendios-Cobertura-Vegetal/ryr5-rs2a) | Boyacá | Long: `fecha_de_inicio`, `municipio`, `tipo_de_incendio`, `rea_total_afectada_ha`. Socrata normaliza "área" → "rea". |

---

## Clima / Normales Climatológicas

| # | Nombre | ID | Enlace | Cobertura | Nota |
|---|--------|----|--------|-----------|------|
| 1 | **Normales Climatológicas — IDEAM** | `nsz2-kzcq` | [Ver dataset](https://www.datos.gov.co/Ambiente-y-Desarrollo-Sostenible/Normales-Climatol-gicas/nsz2-kzcq) | Nacional | Wide: columnas `ene`…`dic` + `anual` por parámetro (`par_metro`) y estación (`estaci_n`). Período 1961-1990. |

---

## Fuente del Dron (MySQL)

Los datos del dron se almacenan localmente en MySQL. No provienen de datos.gov.co.

| Tabla | Descripción |
|-------|-------------|
| `dron_lecturas` | PM2.5, PM10, NO2, O3, ruido (dB), batería, lat/lng, municipio — un registro por captura horaria. |
| `dron_eventos_seguridad` | Eventos de comportamiento detectados por IA (tipo, confianza, URL del medio). |

Carga: script `cron/fetch_dron.php` ejecutado cada hora (Windows Task Scheduler o manualmente).  
Durante desarrollo: usa `cron/sample_dron.json` con datos de Pereira y Dosquebradas.

---

## Cómo agregar un nuevo dataset

1. Verificar que el endpoint responde: `curl "https://www.datos.gov.co/resource/{ID}.json?$limit=1"`
2. Identificar los campos: fecha, valor numérico, municipio, categoría (contaminante).
3. Añadir una entrada en `DATASETS[tema]['sources']` en `src/Config.php`.
4. Si el formato es ancho (wide), usar `'formato' => 'wide'` y definir `valores_wide`.
5. Actualizar este archivo.
