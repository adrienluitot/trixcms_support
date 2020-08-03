<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SupportAlfiorySettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('support_alfiory__settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('enable_priority_support');
            $table->text('sentences')->nullable();
            $table->text('priority_support_articles')->nullable();
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
        Schema::dropIfExists('support_alfiory__settings');
    }
}
