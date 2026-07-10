<?php
/**
 * Alertas ambientales por REGLAS DETERMINÍSTICAS (no LLM).
 *
 * La decisión de alerta es código auditable y reproducible; el LLM solo redacta
 * el mensaje ciudadano de una alerta ya disparada. Umbrales alineados con
 * referencias de la OMS y con evento.php (seguridad).
 */
class Alertas
{
    /** Umbrales (µg/m³ y dB). Documentados para trazabilidad. */
    public const UMBRALES = [
        'pm25_incendio' => 150.0, // PM2.5 muy alto → posible incendio/quema
        'pm10_incendio' => 200.0, // PM10 muy alto → posible incendio/quema
        'ruido_oms'     => 85.0,  // dB: límite de exposición (OMS)
    ];

    /**
     * Evalúa la lectura más reciente del sensor/dron.
     *
     * @return array{alerta:bool, tipo:string, valor:?float, umbral:?float,
     *               parametro:?string, metodo:string}
     */
    public static function evaluar(array $lectura): array
    {
        $pm25  = self::num($lectura['pm25']     ?? null);
        $pm10  = self::num($lectura['pm10']     ?? null);
        $ruido = self::num($lectura['ruido_db'] ?? null);

        if ($pm25 !== null && $pm25 > self::UMBRALES['pm25_incendio']) {
            return self::hit('incendio', 'PM2.5', $pm25, self::UMBRALES['pm25_incendio']);
        }
        if ($pm10 !== null && $pm10 > self::UMBRALES['pm10_incendio']) {
            return self::hit('incendio', 'PM10', $pm10, self::UMBRALES['pm10_incendio']);
        }
        if ($ruido !== null && $ruido > self::UMBRALES['ruido_oms']) {
            return self::hit('ruido', 'ruido_db', $ruido, self::UMBRALES['ruido_oms']);
        }

        return [
            'alerta'    => false,
            'tipo'      => 'ninguna',
            'valor'     => null,
            'umbral'    => null,
            'parametro' => null,
            'metodo'    => 'regla_deterministica',
        ];
    }

    private static function hit(string $tipo, string $param, float $valor, float $umbral): array
    {
        return [
            'alerta'    => true,
            'tipo'      => $tipo,
            'valor'     => $valor,
            'umbral'    => $umbral,
            'parametro' => $param,
            'metodo'    => 'regla_deterministica',
        ];
    }

    private static function num($v): ?float
    {
        return is_numeric($v) ? (float) $v : null;
    }
}
