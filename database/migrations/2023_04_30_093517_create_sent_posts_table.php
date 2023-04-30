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
        Schema::create('sent_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id');
            $table->foreignId('user_id');
            $table->foreignId('post_id');
            $table->unique(['website_id','user_id','post_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sent_posts');
    }
};
