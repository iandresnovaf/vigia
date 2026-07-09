-- Base de datos del Dashboard de Entorno (aire / ruido / seguridad)
-- Ejecutar en MySQL/MariaDB:  mysql -u root -p < sql/schema.sql

CREATE DATABASE IF NOT EXISTS dashboard_entorno
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE dashboard_entorno;

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

-- Eventos de seguridad detectados por la IA (modelo de visión) a partir de video/cámara.
-- Alimenta el cruce con SIEDCO (datos.gov.co) por municipio.
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

-- Migración no destructiva para instalaciones existentes (MariaDB 10.5+ / 11.x).
ALTER TABLE dron_eventos_seguridad ADD COLUMN IF NOT EXISTS municipio    VARCHAR(120) NULL AFTER captured_at;
ALTER TABLE dron_eventos_seguridad ADD COLUMN IF NOT EXISTS cod_muni     VARCHAR(10)  NULL AFTER municipio;
ALTER TABLE dron_eventos_seguridad ADD COLUMN IF NOT EXISTS nivel_alerta VARCHAR(20)  NULL AFTER tipo_comportamiento;

-- Cache opcional de datos abiertos (para no golpear la API en cada carga)
CREATE TABLE IF NOT EXISTS datos_abiertos_cache (
  id           BIGINT AUTO_INCREMENT PRIMARY KEY,
  tema         VARCHAR(20)  NOT NULL,
  municipio    VARCHAR(120) NULL,
  payload_json LONGTEXT     NOT NULL,
  fetched_at   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  KEY idx_cache_tema (tema, municipio)
) ENGINE=InnoDB;
