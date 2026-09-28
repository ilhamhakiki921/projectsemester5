<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('icd10', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();      // J06.9, A00, A00-A09, X ...
            $table->string('title', 500);              // judul dari WHO
            $table->string('kind', 12)->index();       // chapter | block | category
            $table->string('parent_code', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('icd10');
    }
};
