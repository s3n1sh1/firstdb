<?php

use App\Console\BaseMigration;

class CreateTbnewsTable extends BaseMigration
{
    public function up()
    {
        Schema::create('tbnews', function ($table) {
            $table->increments('tnnewsid')->nullable(false)->comment('News IY');
            $table->char('tnname',200)->nullable(false)->comment('News Description');
            $table->longText('tndesc')->nullable(false)->comment('News Content');

            $this->defaultColumn('tn', $table);
        }); 
    }

    public function down()
    {
        Schema::dropIfExists('tbnews');
    }
}
