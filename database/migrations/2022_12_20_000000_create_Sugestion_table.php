<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateSugestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Sugestion', function (Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->string('Title');
            $table->string('Description');
            $table->string('User');
            $table->Date('date_creat');
            $table->integer('etat');
            $table->string('Url');
          
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Sugestion');
    }
}