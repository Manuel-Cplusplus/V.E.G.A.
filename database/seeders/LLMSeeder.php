<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LLMSeeder extends Seeder
{

    public function run(): void
    {
        DB::table('llms')->insert([
            'endpoint' => 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent',
            'provider' => 'Google',
            'model' => 'gemini-2.0-flash',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
