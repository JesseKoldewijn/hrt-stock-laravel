<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("countries", function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string("name_common", 1500);
            $table->string("name_official", 1500);
            $table->string("name_native", 1500);
            $table->string("cca2", 5);
            $table->string("cca3", 5);
            $table->string("currencies", 5000);
            $table->string("time_zones", 500);
            $table->string("flags", 1500);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("countries");
    }
};
