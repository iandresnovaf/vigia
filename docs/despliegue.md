# Guía de despliegue — VigIA (hosting compartido / cPanel)

Pasos para publicar o actualizar VigIA en un hosting (ej. `wellnestfamily.com/vigia`,
base de datos `df4534er_vigia`).

## Requisitos del hosting
- PHP **8.2+** con extensiones: `pdo_mysql`, `curl`, `mbstring`, `openssl`
- MySQL 5.7+ / MariaDB 10.2+
- Acceso a **cPanel** (phpMyAdmin + Administrador de archivos o FTP)

---

## 1. Base de datos (una sola vez o al actualizar el esquema)

1. cPanel → **phpMyAdmin**.
2. Selecciona tu base (ej. `df4534er_vigia`) en el panel izquierdo.
3. Pestaña **Importar** → sube [`sql/migracion_hosting.sql`](../sql/migracion_hosting.sql) → **Continuar**.

Crea las tablas necesarias (incluida `dron_eventos_seguridad`) de forma idempotente:
no borra datos y se puede repetir. Esto resuelve el error `SQLSTATE[42S02] … 1146
Table '…dron_eventos_seguridad' doesn't exist`.

## 2. Subir el código

Opción A — **Git** (si el hosting lo permite): en la carpeta del sitio
```bash
git pull origin master
```
Opción B — **FTP / Administrador de archivos**: sube el contenido de `public/` al
webroot del sitio y `src/`, `sql/`, `cron/` un nivel arriba (fuera del webroot),
respetando la estructura del repositorio.

## 3. Configurar la conexión a la base

Edita `src/Config.php` en el servidor con los datos del hosting:
```php
public const DB = [
    'host' => 'localhost',
    'port' => '3306',
    'name' => 'df4534er_vigia',   // tu base
    'user' => 'df4534er_usuario', // tu usuario MySQL
    'pass' => '••••••••',         // tu contraseña
];
```
> `Config.php` contiene credenciales: **no lo subas a Git**. Mantén los valores solo en el servidor.

## 4. Configurar en la interfaz (⚙️ IA)

Abre el sitio y haz clic en **⚙️ IA**:

- **Asistente IA:** proveedor + modelo + **API key**.
  - **Kimi (plataforma global `platform.kimi.ai`):** VigIA usa el endpoint
    `https://api.moonshot.ai/v1` (global). El de China `api.moonshot.cn` **no responde**
    desde Colombia → daría "Error de red".
  - **Gratis:** OpenRouter (modelos `:free`) o Google Gemini (free tier).
  - Las suscripciones **ChatGPT Plus / Claude Pro NO dan acceso por API**; se necesita una API key.
- **Sensores & Datos:**
  - **URL del sensor/dron:** `https://wellnestfamily.com/vigia/api_datos.php`
    (así "Datos del Dron" lee del sensor real).
  - **Socrata App Token** (opcional): más límite de llamadas a datos.gov.co.

---

## Solución de problemas

| Síntoma | Causa | Solución |
|---|---|---|
| `SQLSTATE … 1146 … dron_eventos_seguridad doesn't exist` | Falta la tabla en la base | Importar `sql/migracion_hosting.sql` (paso 1) |
| "Error de red" al probar/guardar la key de Kimi | Endpoint de China o servidor caído | Código ya usa `api.moonshot.ai`; verifica que el sitio y la base respondan |
| "LLM sin configurar" | Sin API key guardada | ⚙️ IA → pega la key → **Guardar** |
| Predicción/alertas sin datos | Pocos registros | La predicción requiere ≥ 5 días de serie; alertas y predicción funcionan **sin** API key |
| Chat lento | `php -S` local es de un hilo | Solo afecta a desarrollo local, no al hosting |

---

## Verificación rápida (post-despliegue)
1. Abre el sitio → carga **Aire → Pereira → Aplicar** (deben aparecer datos).
2. **Seguridad → Datos del Dron** y **Histórico/Comparación**: sin errores SQL.
3. ⚙️ IA → **Probar conexión** → "✓ Conexión exitosa".
4. Chat: "¿Cómo está el aire en Pereira?" → responde con procedencia.
