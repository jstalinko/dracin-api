<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Apikey;
use Illuminate\Support\Str;

class ApikeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Apikey::create([
            'user_id'       => 1,
            'apikey'        => 'MOBISGROUP-APIKEY-PRODUCTION-8888', // generate API key random
            'start_at'      => now(),
            'end_at'        => now()->addYear(), // expired 1 tahun
            'latest_revoked'=> now(),
            'active'        => true,
        ]);
    }
}
