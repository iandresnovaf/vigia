<?php
/**
 * Analítica estadística determinística y reproducible (sin LLM).
 *
 * Convierte filas de datos abiertos en una serie temporal diaria y calcula
 * tendencia (regresión lineal por mínimos cuadrados), pronóstico a N días con
 * intervalo de confianza, y métricas de error por backtesting (MAE/RMSE/MAPE).
 *
 * El objetivo es separar la "analítica real" (esto) de la "narrativa IA" (el LLM
 * solo explica estos resultados, no los inventa) → rigor técnico defendible.
 */
class Analitica
{
    /**
     * Construye una serie diaria {fecha => valor promedio} a partir de filas heterogéneas.
     * Detecta el campo de fecha y el numérico con fallbacks razonables.
     *
     * @return array<int, array{fecha:string, valor:float}> ordenada ascendente por fecha
     */
    public static function serieDiaria(array $rows): array
    {
        $camposFecha = ['fecha', 'captured_at', 'fecha_hecho', 'fecha_lectura', 'med_fecha_inicio', 'periodo'];
        $camposValor = ['medicion', 'valor', 'cantidad', 'pm25', 'pm10', 'med_concentracion_estandar', 'rea_total_afectada_ha'];

        $acum = []; // fecha(Y-m-d) => [suma, n]
        foreach ($rows as $r) {
            if (!is_array($r)) {
                continue;
            }
            $fecha = null;
            foreach ($camposFecha as $cf) {
                if (isset($r[$cf]) && $r[$cf] !== '') { $fecha = substr((string) $r[$cf], 0, 10); break; }
            }
            $valor = null;
            foreach ($camposValor as $cv) {
                if (isset($r[$cv]) && is_numeric($r[$cv])) { $valor = (float) $r[$cv]; break; }
            }
            if ($fecha === null || $valor === null) {
                continue;
            }
            // Filtro de outliers/errores de sensor (mismo criterio del dashboard).
            if ($valor < 0 || $valor > 100000) {
                continue;
            }
            if (!isset($acum[$fecha])) {
                $acum[$fecha] = [0.0, 0];
            }
            $acum[$fecha][0] += $valor;
            $acum[$fecha][1]++;
        }

        ksort($acum);
        $serie = [];
        foreach ($acum as $fecha => [$suma, $n]) {
            $serie[] = ['fecha' => $fecha, 'valor' => round($suma / max(1, $n), 3)];
        }
        return $serie;
    }

    /** Regresión lineal por mínimos cuadrados sobre el índice temporal (x = 0,1,2,...). */
    public static function regresionLineal(array $serie): array
    {
        $n = count($serie);
        if ($n < 2) {
            $y0 = $n ? (float) $serie[0]['valor'] : 0.0;
            return ['slope' => 0.0, 'intercept' => $y0, 'r2' => 0.0, 'n' => $n];
        }
        $sx = $sy = $sxy = $sxx = 0.0;
        foreach ($serie as $i => $p) {
            $y = (float) $p['valor'];
            $sx += $i; $sy += $y; $sxy += $i * $y; $sxx += $i * $i;
        }
        $den = ($n * $sxx) - ($sx * $sx);
        $slope = $den != 0.0 ? (($n * $sxy) - ($sx * $sy)) / $den : 0.0;
        $intercept = ($sy - $slope * $sx) / $n;

        // Coeficiente de determinación R².
        $meanY = $sy / $n;
        $ssTot = $ssRes = 0.0;
        foreach ($serie as $i => $p) {
            $y   = (float) $p['valor'];
            $yhat = $slope * $i + $intercept;
            $ssTot += ($y - $meanY) ** 2;
            $ssRes += ($y - $yhat) ** 2;
        }
        $r2 = $ssTot > 0 ? max(0.0, 1 - ($ssRes / $ssTot)) : 0.0;

        return ['slope' => $slope, 'intercept' => $intercept, 'r2' => round($r2, 4), 'n' => $n];
    }

    /** Media móvil simple de ventana $v (para suavizado/visualización). */
    public static function mediaMovil(array $serie, int $v = 7): array
    {
        $vals = array_map(fn($p) => (float) $p['valor'], $serie);
        $out  = [];
        for ($i = 0, $n = count($vals); $i < $n; $i++) {
            $ini = max(0, $i - $v + 1);
            $slice = array_slice($vals, $ini, $i - $ini + 1);
            $out[] = round(array_sum($slice) / count($slice), 3);
        }
        return $out;
    }

    /**
     * Pronóstico a $h días por extrapolación lineal + banda de confianza (±1.96·σ residual).
     *
     * @return array{valores:float[], intervalo:array{low:float[],high:float[]},
     *               tendencia:string, slope:float, r2:float}
     */
    public static function pronostico(array $serie, int $h = 7): array
    {
        $reg = self::regresionLineal($serie);
        $n   = $reg['n'];

        // Desviación estándar de los residuos para la banda de confianza.
        $sigma = 0.0;
        if ($n >= 2) {
            $sumSq = 0.0;
            foreach ($serie as $i => $p) {
                $yhat  = $reg['slope'] * $i + $reg['intercept'];
                $sumSq += ((float) $p['valor'] - $yhat) ** 2;
            }
            $sigma = sqrt($sumSq / max(1, $n - 2));
        }
        $margen = 1.96 * $sigma;

        $valores = $low = $high = [];
        for ($k = 1; $k <= $h; $k++) {
            $x   = $n - 1 + $k;
            $yh  = $reg['slope'] * $x + $reg['intercept'];
            $yh  = max(0.0, $yh); // magnitudes físicas no negativas
            $valores[] = round($yh, 2);
            $low[]     = round(max(0.0, $yh - $margen), 2);
            $high[]    = round($yh + $margen, 2);
        }

        return [
            'valores'   => $valores,
            'intervalo' => ['low' => $low, 'high' => $high],
            'tendencia' => self::tendencia($reg['slope'], $serie),
            'slope'     => round($reg['slope'], 4),
            'r2'        => $reg['r2'],
        ];
    }

    /** Clasifica la tendencia según la pendiente relativa al promedio de la serie. */
    private static function tendencia(float $slope, array $serie): string
    {
        $vals = array_map(fn($p) => (float) $p['valor'], $serie);
        $mean = $vals ? array_sum($vals) / count($vals) : 0.0;
        if ($mean <= 0) {
            return $slope > 0 ? 'alza' : ($slope < 0 ? 'baja' : 'estable');
        }
        $rel = $slope / $mean; // cambio diario relativo
        if ($rel >= 0.01) {
            return 'alza';
        }
        if ($rel <= -0.01) {
            return 'baja';
        }
        return 'estable';
    }

    /**
     * Backtesting: entrena con la serie menos las últimas $holdout observaciones,
     * pronostica ese tramo y compara → MAE, RMSE, MAPE.
     *
     * @return array{mae:?float, rmse:?float, mape:?float, n:int}
     */
    public static function backtest(array $serie, int $holdout = 7): array
    {
        $n = count($serie);
        if ($n < $holdout + 3) {
            return ['mae' => null, 'rmse' => null, 'mape' => null, 'n' => 0];
        }
        $train = array_slice($serie, 0, $n - $holdout);
        $test  = array_slice($serie, $n - $holdout);
        $reg   = self::regresionLineal($train);
        $base  = count($train);

        $absErr = $sqErr = $pctErr = 0.0;
        $mapeN  = 0;
        foreach ($test as $j => $p) {
            $x    = $base + $j;
            $yhat = max(0.0, $reg['slope'] * $x + $reg['intercept']);
            $y    = (float) $p['valor'];
            $e    = abs($y - $yhat);
            $absErr += $e;
            $sqErr  += ($y - $yhat) ** 2;
            if ($y != 0.0) { $pctErr += $e / abs($y); $mapeN++; }
        }
        $m = count($test);
        return [
            'mae'  => round($absErr / $m, 3),
            'rmse' => round(sqrt($sqErr / $m), 3),
            'mape' => $mapeN ? round(100 * $pctErr / $mapeN, 2) : null,
            'n'    => $m,
        ];
    }

    /** Deriva un nivel de confianza legible a partir de R² y MAPE. */
    public static function nivelConfianza(float $r2, ?float $mape): string
    {
        if ($mape !== null) {
            if ($mape <= 15 && $r2 >= 0.5) return 'alta';
            if ($mape <= 35) return 'media';
            return 'baja';
        }
        if ($r2 >= 0.6) return 'alta';
        if ($r2 >= 0.3) return 'media';
        return 'baja';
    }
}
