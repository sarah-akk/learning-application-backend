<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('course_types', function (Blueprint $table) {
            $table->id();
            $table->String('title',50);
            $table->mediumInteger('parent_id')->nullable();
            $table->text('description')->nullable();
            $table->mediumInteger('order')->nullable();
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('course_types');
    }
};
