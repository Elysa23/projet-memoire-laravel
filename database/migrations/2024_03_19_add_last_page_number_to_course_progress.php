<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('course_progress', function (Blueprint $table) {
            if (!Schema::hasColumn('course_progress', 'last_page_number')) {
                $table->integer('last_page_number')->default(1)->after('course_id');
            }
        });
    }

    public function down()
    {
        Schema::table('course_progress', function (Blueprint $table) {
            if (Schema::hasColumn('course_progress', 'last_page_number')) {
                $table->dropColumn('last_page_number');
            }
        });
    }
}; 