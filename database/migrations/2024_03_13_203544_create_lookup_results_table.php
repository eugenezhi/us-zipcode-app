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
        Schema::create('lookup_results', function (Blueprint $table) {
            $table->increments('id');
            $table->string('city');
            $table->unsignedInteger('state_id');
            $table->jsonb('result')->default(null)->nullable();;
            $table->timestamps();

            $table->index(['city', 'state_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lookup_results');
    }
};
