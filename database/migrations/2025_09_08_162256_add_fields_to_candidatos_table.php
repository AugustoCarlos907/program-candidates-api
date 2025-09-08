<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('candidatos', function (Blueprint $table) {
            $table->enum('genero', ['M', 'F'])->nullable();
            $table->string('telefone', 11)->nullable();
            $table->date('data_nascimento')->nullable();
            $table->string('nacionalidade', 100)->nullable();
            $table->string('endereco')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidatos', function (Blueprint $table) {
            $table->dropColumn([
                'genero',
                'telefone',
                'data_nascimento',
                'nacionalidade',
                'endereco'
            ]);
        });
    }
};
