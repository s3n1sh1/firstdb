<?php
namespace App\Console;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use DB;

class BaseMigration extends Migration
{
    
    public function defaultColumn($Prefix, Blueprint $table) 
    {
        $table->longText($Prefix.'remk')->nullable(true)->comment('Remark');
        $table->char($Prefix.'rgid',50)->nullable(false)->comment('Add By');
        $table->datetime($Prefix.'rgdt')->nullable(false)->comment('Add On');
        $table->char($Prefix.'chid',50)->nullable(false)->comment('Change By');
        $table->datetime($Prefix.'chdt')->nullable(false)->comment('Change On');
        $table->integer($Prefix.'chno')->nullable(false)->default(((0)))->comment('Change Num');
        $table->boolean($Prefix.'dlfg')->nullable(false)->default(((0)))->comment('Delete Flag');
        $table->boolean($Prefix.'dpfg')->nullable(false)->default(((1)))->comment('Display Flag');
        $table->char($Prefix.'srce',50)->nullable(true)->comment('Source');
        $table->longText($Prefix.'note')->nullable(true)->comment('Notes');
    }

} 

?>