<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaintenanceLookupSeeder extends Seeder
{
    /**
     * Seed lookup/maintenance tables from captured production-like data.
     *
     * @return void
     */
    public function run()
    {
        $this->seedTable('religions', require __DIR__ . '/data/religions.php');
        $this->seedTable('municipalities', require __DIR__ . '/data/municipalities.php');
        $this->seedTable('nationalities', require __DIR__ . '/data/nationalities.php');
        $this->seedTable('purposes', require __DIR__ . '/data/purposes.php');
    }

    /**
     * Upsert rows by id and preserve source IDs for deterministic references.
     *
     * @param string $table
     * @param array $rows
     * @return void
     */
    protected function seedTable($table, array $rows)
    {
        if (empty($rows)) {
            return;
        }

        $driver = DB::connection()->getDriverName();
        if ($driver === 'sqlsrv') {
            DB::statement("SET IDENTITY_INSERT {$table} ON");
        }

        foreach ($rows as $row) {
            $existing = DB::table($table)->where('id', $row['id'])->exists();
            $payload = $row;
            unset($payload['id']);

            if ($existing) {
                DB::table($table)->where('id', $row['id'])->update($payload);
            } else {
                DB::table($table)->insert($row);
            }
        }

        if ($driver === 'sqlsrv') {
            DB::statement("SET IDENTITY_INSERT {$table} OFF");
        }
    }
}
