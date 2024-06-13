<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('employee_name', 100);
            $table->enum('gender', ['male', 'female']);
            $table->string('position');
            $table->enum('active_status', ['0', '1'])->default('1'); // active_status dengan default '1'
            $table->string('photo')->nullable(); // Kolom untuk menyimpan nama file foto, bisa null
            $table->date('employment_start_date')->nullable(); // Tanggal mulai kerja, bisa null
            $table->date('employment_end_date')->nullable(); // Tanggal berakhir kerja, bisa null
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
        Schema::dropIfExists('employees');
    }
}
