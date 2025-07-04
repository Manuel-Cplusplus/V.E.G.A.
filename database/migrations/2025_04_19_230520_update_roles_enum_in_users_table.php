<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM(
            'Appassionato',
            'Ricercatore',
            'Esperto',
            'Divulgatore Scientifico',
            'Professore',
            'Altro'
        ) NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('Appassionato', 'Ricercatore') NULL");
    }
};
