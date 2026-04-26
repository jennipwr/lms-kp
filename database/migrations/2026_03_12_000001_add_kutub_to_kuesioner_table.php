<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kuesioner', function (Blueprint $table) {
            if (!Schema::hasColumn('kuesioner', 'kutub_a')) {
                $table->string('kutub_a')->nullable();
            }
            if (!Schema::hasColumn('kuesioner', 'kutub_b')) {
                $table->string('kutub_b')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kuesioner', function (Blueprint $table) {
            if (Schema::hasColumn('kuesioner', 'kutub_a')) {
                $table->dropColumn('kutub_a');
            }
            if (Schema::hasColumn('kuesioner', 'kutub_b')) {
                $table->dropColumn('kutub_b');
            }
        });
    }
};
