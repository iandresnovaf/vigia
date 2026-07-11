-- ============================================================================
-- VigIA — Migración para HOSTING (phpMyAdmin / cPanel)
-- ============================================================================
-- Cómo usar:
--   1) cPanel → phpMyAdmin
--   2) Selecciona TU base de datos (ej. df4534er_vigia) en el panel izquierdo
--   3) Pestaña "Importar" → elige este archivo → "Continuar"
--      (o pestaña "SQL" → pega todo → "Continuar")
--
-- Es idempotente: se puede ejecutar varias veces sin borrar datos
-- (usa CREATE TABLE IF NOT EXISTS). NO crea ni cambia la base de datos.
-- ============================================================================

-- Lecturas de sensores del dron (aire + ruido)
CREATE TABLE IF NOT EXISTS dron_lecturas (
  id          BIGINT AUTO_INCREMENT PRIMARY KEY,
  device_id   VARCHAR(64)  NOT NULL DEFAULT 'dron-01',
  captured_at DATETIME     NOT NULL,
  municipio   VARCHAR(120) NULL,
  cod_muni    VARCHAR(10)  NULL,
  lat         DECIMAL(10,7) NULL,
  lng         DECIMAL(10,7) NULL,
  pm25        DECIMAL(8,2) NULL,
  pm10        DECIMAL(8,2) NULL,
  no2         DECIMAL(8,2) NULL,
  o3          DECIMAL(8,2) NULL,
  ruido_db    DECIMAL(6,2) NULL,
  bateria     TINYINT      NULL,
  raw_json    JSON         NULL,
  created_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_lectura (device_id, captured_at),
  KEY idx_lectura_muni (municipio, captured_at)
) ENGINE=InnoDB;

-- Eventos de seguridad detectados por el modelo de visión (× cruce SIEDCO)
-- >>> Esta es la tabla que faltaba y causaba el error SQLSTATE 1146. <<<
CREATE TABLE IF NOT EXISTS dron_eventos_seguridad (
  id                  BIGINT AUTO_INCREMENT PRIMARY KEY,
  device_id           VARCHAR(64)  NOT NULL DEFAULT 'dron-01',
  captured_at         DATETIME     NOT NULL,
  municipio           VARCHAR(120) NULL,
  cod_muni            VARCHAR(10)  NULL,
  lat                 DECIMAL(10,7) NULL,
  lng                 DECIMAL(10,7) NULL,
  tipo_comportamiento VARCHAR(80)  NOT NULL,
  nivel_alerta        VARCHAR(20)  NULL,
  confianza           DECIMAL(4,3) NULL,
  media_url           VARCHAR(500) NULL,
  ai_result_json      JSON         NULL,
  created_at          TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_evento (device_id, captured_at, tipo_comportamiento),
  KEY idx_evento_fecha (captured_at),
  KEY idx_evento_muni (municipio, captured_at)
) ENGINE=InnoDB;

-- Configuración (LLM, sensor URL, Socrata token). La app la crea sola,
-- se incluye aquí por si la base es nueva.
CREATE TABLE IF NOT EXISTS llm_config (
  cfg_key    VARCHAR(64) NOT NULL PRIMARY KEY,
  cfg_val    TEXT        NOT NULL,
  updated_at TIMESTAMP   NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Cache opcional de datos abiertos
CREATE TABLE IF NOT EXISTS datos_abiertos_cache (
  id           BIGINT AUTO_INCREMENT PRIMARY KEY,
  tema         VARCHAR(20)  NOT NULL,
  municipio    VARCHAR(120) NULL,
  payload_json LONGTEXT     NOT NULL,
  fetched_at   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  KEY idx_cache_tema (tema, municipio)
) ENGINE=InnoDB;

-- ----------------------------------------------------------------------------
-- Nota (solo si YA tenías una versión ANTIGUA de dron_eventos_seguridad sin
-- las columnas nuevas). En MariaDB puedes ejecutar:
--   ALTER TABLE dron_eventos_seguridad ADD COLUMN IF NOT EXISTS municipio    VARCHAR(120) NULL AFTER captured_at;
--   ALTER TABLE dron_eventos_seguridad ADD COLUMN IF NOT EXISTS cod_muni     VARCHAR(10)  NULL AFTER municipio;
--   ALTER TABLE dron_eventos_seguridad ADD COLUMN IF NOT EXISTS nivel_alerta VARCHAR(20)  NULL AFTER tipo_comportamiento;
-- En MySQL 8 (no soporta IF NOT EXISTS en ADD COLUMN) hazlo sin "IF NOT EXISTS"
-- solo para las columnas que falten. Si la tabla se creó con este archivo,
-- ya trae todas las columnas y NO necesitas estos ALTER.
-- ----------------------------------------------------------------------------
