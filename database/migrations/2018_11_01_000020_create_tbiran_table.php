<?php

use App\Console\BaseMigration;

class CreateTbiranTable extends BaseMigration
{
    public function up()
    {
        Schema::create('tbiran', function ($table) {
            $table->increments('tiiranid')->nullable(false)->comment('Iuran IY');
            $table->unsignedInteger('tiuserid')->nullable(false)->comment('User IY');
            $table->char('timont',6)->nullable(false)->comment('Periode Bulan');
            $table->decimal('tiiran',14,2)->nullable(true)->comment('Iuran');

            $this->defaultColumn('ti', $table);

            $table->unique(['tiuserid','timont']);
            $table->foreign('tiuserid')->references('tuuserid')->on('tbuser');
        }); 
    }

    public function down()
    {
        Schema::dropIfExists('tbiran');
    }
}
