<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrintCountToFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('forms', function (Blueprint $table) {
        $table->integer('print_count')->default(0); // ค่าเริ่มต้นเป็น 0
    });
}

public function down()
{
    Schema::table('forms', function (Blueprint $table) {
        $table->dropColumn('print_count');
    });
}
}
