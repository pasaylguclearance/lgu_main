<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SampleDataSeeder extends Seeder
{
    /**
     * Seed sample data across application tables.
     *
     * @return void
     */
    public function run()
    {
        $now = now();

        DB::table('dashboards')->insert([
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('fees')->insert([
            'code' => 'CLR-001',
            'description' => 'Police clearance processing fee',
            'cost' => '150.00',
            'status' => '1',
            'created_by' => 1,
            'updated_by' => 1,
            'deleted_at' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('finger_prints')->insert([
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $municipalityId = DB::table('municipalities')->insertGetId([
            'municipality' => 'Pasay City',
            'deleted_at' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $nationalityId = DB::table('nationalities')->insertGetId([
            'nationality' => 'Filipino',
            'deleted_at' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $purposeId = DB::table('purposes')->insertGetId([
            'purpose' => 'Employment',
            'cost' => 150.00,
            'deleted_at' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $religionId = DB::table('religions')->insertGetId([
            'religion' => 'Roman Catholic',
            'deleted_at' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('hit_verifications')->insert([
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $newApplicationId = DB::table('new_applications')->insertGetId([
            'application_no' => 'APP-' . date('Ymd') . '-0001',
            'cedula_no' => 'C-1234567',
            'or_no' => 'OR-10001',
            'issued_at' => 'Pasay City',
            'issued_date' => date('Y-m-d'),
            'issued_or_date' => date('Y-m-d'),
            'firstname' => 'Maria',
            'middlename' => 'Santos',
            'lastname' => 'Reyes',
            'suffix' => null,
            'purpose_id' => $purposeId,
            'application_type' => 'NEW',
            'house_no' => '123',
            'street' => 'Roxas Blvd',
            'barangay' => 'Barangay 1',
            'municipality_id' => $municipalityId,
            'province' => 'Metro Manila',
            'country' => 'Philippines',
            'birthdate' => '1995-06-15',
            'birth_place' => 'Pasay City',
            'gender' => 'FEMALE',
            'nationality_id' => $nationalityId,
            'civil_status' => 'SINGLE',
            'religion_id' => $religionId,
            'height' => '165',
            'weight' => '58',
            'hair_color' => 'Black',
            'eye_color' => 'Brown',
            'contact_number' => '09171234567',
            'mole' => 'None',
            'scar' => 'None',
            'tattoo' => 'None',
            'birthmark' => 'None',
            'harelip' => 'None',
            'skin_tag' => 'None',
            'occupation_id' => '1',
            'occupation' => 'Office Staff',
            'employer' => 'Sample Corp',
            'employer_address' => 'Makati City',
            'blood_type' => 'O+',
            'contact_person' => 'Jose Reyes',
            'contact_no' => '09179876543',
            'contact_address' => 'Pasay City',
            'ucid' => 'UCID-0001',
            'finger_print_right' => null,
            'finger_print_left' => null,
            'fingerprint_right_data' => null,
            'fingerprint_left_data' => null,
            'picture' => null,
            'signature' => null,
            'status' => 'ACTIVE',
            'reference_num' => strtoupper(Str::random(10)),
            'type' => 'NEW',
            'stage' => 'APPLICATION',
            'deleted_at' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $applicationId = DB::table('applications')->insertGetId([
            'new_application_id' => $newApplicationId,
            'type' => 'NEW',
            'date' => date('Y-m-d'),
            'status' => 'PAID',
            'finding' => null,
            'deleted_at' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('payments')->insert([
            'application_id' => $applicationId,
            'specification' => 'Police Clearance',
            'or_number' => 'OR-20001',
            'ucid' => 'UCID-0001',
            'date_of_expiration' => date('Y-m-d', strtotime('+6 months')),
            'amount' => '150.00',
            'created_by' => '1',
            'updated_by' => '1',
            'deleted_at' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('password_resets')->insert([
            'email' => 'superadmin@gmail.com',
            'token' => Str::random(60),
            'created_at' => $now,
        ]);

        DB::table('renewals')->insert([
            'application_id' => $newApplicationId,
            'date_renew' => date('Y-m-d'),
            'date_expiry' => date('Y-m-d', strtotime('+6 months')),
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
