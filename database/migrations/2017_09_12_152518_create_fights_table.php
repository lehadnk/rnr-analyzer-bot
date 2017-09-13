<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fights', function (Blueprint $table) {
            $table->increments('id');
            $table->date('raid_date');
            $table->string('log_id');
            $table->integer('fight_id');
            $table->integer('start_time');
            $table->integer('fight_length');
            $table->integer('boss_id');
            $table->integer('difficulty_id');
            $table->boolean('is_kill');
            $table->float('percentage');
            $table->string('boss_name');
            $table->timestamps();

            $table->unique(['log_id', 'fight_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fights');
    }
}
