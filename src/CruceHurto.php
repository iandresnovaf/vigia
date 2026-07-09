<?php
require_once __DIR__ . '/Config.php';
require_once __DIR__ . '/SocrataClient.php';

/**
 * Motor de cruce: relaciona una clase detectada por el modelo de visión (TSN)
 * con las estadísticas históricas de hurto de datos.gov.co (SIEDCO), por municipio.
 *
 * El dataset SIEDCO de cada modalidad trae {fecha_hecho, municipio, cantidad};
 * agregamos el total del municipio y comparamos el periodo reciente contra el
 * anterior para estimar una tendencia.
 */
class CruceHurto
{
    /** Normaliza una clase del TSN a la clave del mapa (minúsculas, sin acentos/espacios). */
    public static function normalizarClase(string $clase): string
    {
        $c = strtolower(trim($clase));
        $c = strtr($c, ['á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u']);
        return preg_replace('/[^a-z]/', '', $c);
    }

    /**
     * Cruza la clase detectada con el dataset SIEDCO correspondiente.
     *
     * @return array{modalidad:string, dataset:?string, casos_recientes:int,
     *               casos_previos:int, tendencia:string, narrativa:string}
     */
    public static function analizar(string $claseCV, string $municipio): array
    {
        $clave = self::normalizarClase($claseCV);
        $map   = Config::CV_CLASS_MAP[$clave] ?? null;
        $modalidad = $map['modalidad'] ?? 'Evento de seguridad';
        $dataset   = $map['dataset']   ?? null;

        $base = [
            'modalidad'       => $modalidad,
            'dataset'         => $dataset,
            'casos_recientes' => 0,
            'casos_previos'   => 0,
            'tendencia'       => 'sin_datos',
            'narrativa'       => '',
        ];

        // Sin dataset (normal) o sin municipio → no hay cruce estadístico.
        if ($dataset === null || trim($municipio) === '') {
            $base['narrativa'] = $dataset === null
                ? 'Actividad sin cruce estadístico de hurto.'
                : 'Detección registrada; especifica un municipio para cruzar con SIEDCO.';
            return $base;
        }

        try {
            $safe   = str_replace("'", "''", strtoupper(trim($municipio)));
            $rows   = SocrataClient::query($dataset, [
                '$select' => 'fecha_hecho, cantidad',
                '$where'  => "upper(municipio)='{$safe}'",
                '$order'  => 'fecha_hecho DESC',
                '$limit'  => 2000,
            ]);
        } catch (Throwable $e) {
            $base['narrativa'] = 'No se pudo consultar SIEDCO (' . $modalidad . ') en este momento.';
            return $base;
        }

        // Divide en dos ventanas de 90 días: reciente vs. previa.
        $ahora     = time();
        $ventana   = 90 * 86400;
        $recientes = 0;
        $previos   = 0;
        foreach ($rows as $r) {
            $ts = strtotime(substr((string) ($r['fecha_hecho'] ?? ''), 0, 10));
            if ($ts === false) {
                continue;
            }
            $cant = (int) ($r['cantidad'] ?? 1);
            $edad = $ahora - $ts;
            if ($edad <= $ventana) {
                $recientes += $cant;
            } elseif ($edad <= 2 * $ventana) {
                $previos += $cant;
            }
        }

        $tendencia = self::tendencia($recientes, $previos);
        $base['casos_recientes'] = $recientes;
        $base['casos_previos']   = $previos;
        $base['tendencia']       = $tendencia;
        $base['narrativa']       = self::narrar($modalidad, $municipio, $recientes, $tendencia);
        return $base;
    }

    private static function tendencia(int $recientes, int $previos): string
    {
        if ($recientes === 0 && $previos === 0) {
            return 'sin_datos';
        }
        if ($previos === 0) {
            return $recientes > 0 ? 'alza' : 'estable';
        }
        $ratio = ($recientes - $previos) / $previos;
        if ($ratio >= 0.15) {
            return 'alza';
        }
        if ($ratio <= -0.15) {
            return 'baja';
        }
        return 'estable';
    }

    private static function narrar(string $modalidad, string $municipio, int $recientes, string $tendencia): string
    {
        $muni = ucwords(strtolower(trim($municipio)));
        $tendTxt = [
            'alza'    => 'con tendencia al alza',
            'baja'    => 'con tendencia a la baja',
            'estable' => 'con tendencia estable',
            'sin_datos' => '',
        ][$tendencia] ?? '';

        if ($recientes === 0 && $tendencia === 'sin_datos') {
            return "SIEDCO no registra casos recientes de {$modalidad} en {$muni}.";
        }
        return "SIEDCO registra {$recientes} casos de {$modalidad} en {$muni} en los últimos 90 días {$tendTxt}.";
    }
}
