<?php
/**
 * Configuración central del dashboard.
 * Copia este archivo si necesitas versionarlo sin secretos (ver config/config.example.php).
 */
class Config
{
    /** Conexión MySQL. */
    public const DB = [
        'host' => '127.0.0.1',
        'port' => '3306',
        'name' => 'dashboard_entorno',
        'user' => 'root',
        'pass' => '',
    ];

    public const SOCRATA_BASE      = 'https://www.datos.gov.co/resource/';
    public const SOCRATA_APP_TOKEN = '';
    public const CURL_SSL_VERIFY   = true;

    public const DRON_URL       = '';
    public const DRON_SAMPLE    = __DIR__ . '/../cron/sample_dron.json';
    public const DRON_DEVICE_ID = 'dron-01';

    public const AI_SERVICE_URL = '';

    /**
     * Grupos de navegación del topbar (menú desplegable).
     * Cada grupo referencia claves de DATASETS.
     */
    public const GRUPOS = [
        'ambiente'  => ['label' => '🌿 Ambiente',  'temas' => ['aire', 'ruido', 'incendios', 'clima']],
        'seguridad' => ['label' => '🛡️ Seguridad', 'temas' => ['seguridad']],
    ];

    /**
     * Cada tema tiene un arreglo "sources" de datasets de datos.gov.co (Socrata SODA).
     * Todos verificados vivos por API el 2026-07-01.
     *
     * formato "long"  → una fila = una medición (hay campo_categoria).
     * formato "wide"  → una fila = N mediciones (valores_wide define col → etiqueta).
     */
    public const DATASETS = [

        /* ───────────────── CALIDAD DEL AIRE ───────────────── */
        'aire' => [
            'label'      => 'Calidad del Aire',
            'unidad'     => 'µg/m³',
            'dron_campo' => 'pm25',
            'sources'    => [
                [
                    'id'              => '53gx-j5pc',
                    'label'           => 'Risaralda — CVC',
                    'url'             => 'https://www.datos.gov.co/Ambiente-y-Desarrollo-Sostenible/Calidad-del-Aire/53gx-j5pc',
                    'formato'         => 'long',
                    'campo_fecha'     => 'fecha',
                    'campo_valor'     => 'medicion',
                    'campo_municipio' => 'municipio',
                    'campo_estacion'  => 'estacion',
                    'campo_categoria' => 'diametro_aerodinamico',
                    'nota'            => 'Pereira, Dosquebradas, La Virginia y Santa Rosa de Cabal (Risaralda). Datos hasta 2023.',
                ],
                [
                    'id'              => 'g4t8-zkc3',
                    'label'           => 'Nacional — IDEAM',
                    'url'             => 'https://www.datos.gov.co/Ambiente-y-Desarrollo-Sostenible/Calidad-del-Aire-en-Colombia/g4t8-zkc3',
                    'formato'         => 'long',
                    'campo_fecha'     => 'med_fecha_inicio',
                    'campo_valor'     => 'med_concentracion_estandar',
                    'campo_municipio' => 'municipio',
                    'campo_estacion'  => 'nombre_est',
                    'campo_categoria' => 'msfl_code',
                    'nota'            => 'Dataset nacional del IDEAM desde 2020. Incluye PM10, PM2.5, SO2, NO2, O3 y más.',
                ],
                [
                    'id'              => 'yspz-pxxn',
                    'label'           => 'SVCA — Caldas/Risaralda',
                    'url'             => 'https://www.datos.gov.co/Ambiente-y-Desarrollo-Sostenible/Sistema-de-Informacion-sobre-Calidad-del-Aire-PM10/yspz-pxxn',
                    'formato'         => 'wide',
                    'campo_fecha'     => 'fecha_lectura',
                    'campo_municipio' => null,
                    'campo_estacion'  => 'estacion',
                    'valores_wide'    => ['pm10' => 'PM10', 'pm25' => 'PM2.5'],
                    'nota'            => 'SVCA — estaciones en Caldas y Risaralda. Sin filtro de municipio (no disponible en API).',
                ],
                [
                    'id'              => 'aghd-ge2f',
                    'label'           => 'Duitama — Boyacá',
                    'url'             => 'https://www.datos.gov.co/Ambiente-y-Desarrollo-Sostenible/Calidad-del-Aire-Municipio-de-Duitama/aghd-ge2f',
                    'formato'         => 'wide',
                    'campo_fecha'     => 'fecha',
                    'campo_municipio' => null,
                    'campo_estacion'  => 'nombre_equipo',
                    'municipio_fijo'  => 'Duitama',
                    'valores_wide'    => ['pm10' => 'PM10', 'pm2_5' => 'PM2.5'],
                    'nota'            => 'Estaciones de monitoreo en Duitama, Boyacá. También incluye CO, CO2 y parámetros meteorológicos.',
                ],
            ],
        ],

        /* ───────────────── CALIDAD DEL RUIDO ───────────────── */
        'ruido' => [
            'label'      => 'Calidad del Ruido',
            'unidad'     => 'dB',
            'dron_campo' => 'ruido_db',
            'sources'    => [], // Sin dataset tabular por API en datos.gov.co
            'nota'       => 'Los datasets de ruido en datos.gov.co son mapas/archivos GIS, no consultables por API tabular. La fuente principal de ruido es el sensor del dron (campo ruido_db).',
        ],

        /* ───────────────── INCENDIOS FORESTALES ───────────────── */
        'incendios' => [
            'label'      => 'Incendios Forestales',
            'unidad'     => 'ha',
            'dron_campo' => null,
            'sources'    => [
                [
                    'id'              => 'ryr5-rs2a',
                    'label'           => 'CORPOBOYACÁ — Boyacá',
                    'url'             => 'https://www.datos.gov.co/Ambiente-y-Desarrollo-Sostenible/Incendios-Cobertura-Vegetal/ryr5-rs2a',
                    'formato'         => 'long',
                    'campo_fecha'     => 'fecha_de_inicio',
                    'campo_valor'     => 'rea_total_afectada_ha',
                    'campo_municipio' => 'municipio',
                    'campo_estacion'  => null,
                    'campo_categoria' => 'tipo_de_incendio',
                    'nota'            => 'Boyacá — incendios de cobertura vegetal registrados por CORPOBOYACÁ.',
                ],
            ],
        ],

        /* ───────────────── CLIMA / NORMALES CLIMATOLÓGICAS ───────────────── */
        'clima' => [
            'label'      => 'Clima / Normales',
            'unidad'     => 'var',
            'dron_campo' => null,
            'sources'    => [
                [
                    'id'              => 'nsz2-kzcq',
                    'label'           => 'Normales Climatológicas — IDEAM',
                    'url'             => 'https://www.datos.gov.co/Ambiente-y-Desarrollo-Sostenible/Normales-Climatol-gicas/nsz2-kzcq',
                    'formato'         => 'wide',
                    'campo_fecha'     => 'periodo',
                    'campo_municipio' => 'municipio',
                    'campo_estacion'  => 'estaci_n',
                    'campo_categoria' => 'par_metro',
                    'valores_wide'    => [
                        'ene' => 'Ene', 'feb' => 'Feb', 'mar' => 'Mar', 'abr' => 'Abr',
                        'may' => 'May', 'jun' => 'Jun', 'jul' => 'Jul', 'ago' => 'Ago',
                        'sep' => 'Sep', 'oct' => 'Oct', 'nov' => 'Nov', 'dic' => 'Dic',
                    ],
                    'nota'            => 'Normales climatológicas IDEAM (1961-2010, nacional). Promedios mensuales por parámetro y estación.',
                ],
            ],
        ],

        /* ───────────────── SEGURIDAD ───────────────── */
        'seguridad' => [
            'label'      => 'Análisis de Entorno (Seguridad)',
            'unidad'     => 'casos',
            'dron_campo' => null,
            'sources'    => [
                [
                    'id'              => '4rxi-8m8d',
                    'label'           => 'Hurto a Personas — SIEDCO',
                    'url'             => 'https://www.datos.gov.co/Seguridad-y-Defensa/HURTO-PERSONAS/4rxi-8m8d/data',
                    'formato'         => 'long',
                    'campo_fecha'     => 'fecha_hecho',
                    'campo_valor'     => 'cantidad',
                    'campo_municipio' => 'municipio',
                    'campo_estacion'  => null,
                    'campo_categoria' => null,
                    'nota'            => 'Policía Nacional SIEDCO. Cobertura nacional, hasta 2026.',
                ],
                [
                    'id'              => 'm8fd-ahd9',
                    'label'           => 'Homicidios — SIEDCO',
                    'url'             => 'https://www.datos.gov.co/Seguridad-y-Defensa/HOMICIDIO/m8fd-ahd9',
                    'formato'         => 'long',
                    'campo_fecha'     => 'fecha_hecho',
                    'campo_valor'     => 'cantidad',
                    'campo_municipio' => 'municipio',
                    'campo_estacion'  => null,
                    'campo_categoria' => null,
                    'nota'            => 'Policía Nacional SIEDCO. Desde 2003. Incluye arma, modalidad y caracterización.',
                ],
                [
                    'id'              => 'jr6v-i33g',
                    'label'           => 'Lesiones Personales — SIEDCO',
                    'url'             => 'https://www.datos.gov.co/Seguridad-y-Defensa/LESIONES-PERSONALES/jr6v-i33g',
                    'formato'         => 'long',
                    'campo_fecha'     => 'fecha_hecho',
                    'campo_valor'     => 'cantidad',
                    'campo_municipio' => 'municipio',
                    'campo_estacion'  => null,
                    'campo_categoria' => null,
                    'nota'            => 'Policía Nacional SIEDCO. Lesiones personales desde 2003.',
                ],
            ],
        ],
    ];
}
