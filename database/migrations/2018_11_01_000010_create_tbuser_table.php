<?php

use App\Console\BaseMigration;

class CreateTbuserTable extends BaseMigration
{
    public function up()
    {
        Schema::create('tbuser', function ($table) {
            $table->increments('tuuserid')->nullable(false)->comment('User IY');
            $table->char('tuuser',50)->nullable(false)->comment('Username');
            $table->char('tupass',100)->nullable(false)->comment('Password');
            $table->char('tuname',100)->nullable(true)->comment('User Name');
            $table->decimal('tuiran',24,10)->nullable(true)->comment('Iuran');

            $this->defaultColumn('tu', $table);

            $table->unique('tuuser');
        }); 
    }

    public function down()
    {
        Schema::dropIfExists('tbuser');
    }
}
