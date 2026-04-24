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
        Schema::create('user_mappings', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id'); // Laravel user
        $table->unsignedBigInteger('model_user_id'); // ALS user index
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_mappings');
    }
};
