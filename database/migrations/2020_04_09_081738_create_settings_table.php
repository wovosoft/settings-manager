<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('settings-manager.table_name'), function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->string('type')->nullable();
            $table->string('group')->nullable();
            $table->string('value')->nullable();
            $table->text('options')->nullable();    //mainly for b-form-select
            $table->timestamps();
            $table->unique(['key', 'group']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('settings-manager.table_name'));
    }
}
