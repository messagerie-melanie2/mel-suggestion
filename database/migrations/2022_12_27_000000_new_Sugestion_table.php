<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class NewSugestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Vote', function (Blueprint $table) {
            $table->bigIncrements('id')->unique();
        
            $table->string('email');
            $table->Date('date_vote');
            $table->integer('id-sugestion');
            $table->string('Url');
        });
          
    
    }
public function down()
{
    Schema::dropIfExists('Vote');
}
}