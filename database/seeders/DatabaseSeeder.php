<?php

namespace Database\Seeders;

use App\Models\User;
use Egulias\EmailValidator\EmailParser;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/* Modified by: I Pinguini */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //$this->call(LLMSeeder::class);
        $this->call(LearnPromptSeeder::class);
    }
}
