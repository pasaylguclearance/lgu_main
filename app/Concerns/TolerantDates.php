<?php

namespace App\Concerns;

/**
 * Accept datetime strings with fractional seconds from the database.
 *
 * Laravel 5.8 parses date attributes with Carbon::createFromFormat('Y-m-d H:i:s')
 * and throws "Trailing data" when the driver returns e.g. "2026-09-21 01:16:23.000"
 * (SQL Server datetime, MySQL DATETIME(6)). Reading created_at, or the updated_at
 * dirty-check inside save(), then fails on that environment. Strip the fraction
 * (and an ISO "T") before handing the value to the framework.
 */
trait TolerantDates
{
    protected function asDateTime($value)
    {
        if (is_string($value)) {
            $value = trim($value);
            // "2026-09-21T01:16:23.000Z" / "2026-09-21 01:16:23.000000" -> "2026-09-21 01:16:23"
            if (preg_match('/^(\d{4}-\d{2}-\d{2})[T ](\d{2}:\d{2}:\d{2})(?:\.\d+)?(?:Z|[+-]\d{2}:?\d{2})?$/', $value, $m)) {
                $value = $m[1] . ' ' . $m[2];
            }
        }

        return parent::asDateTime($value);
    }
}
