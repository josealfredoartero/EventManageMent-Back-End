<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment__publications', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->foreignId('id_user')->constrained('users')->cascadeOnDelete();
            $table->foreignId('id_publication')->constrained('publications')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comment__publications');
    }
};
