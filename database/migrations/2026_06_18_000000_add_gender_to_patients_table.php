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
        Schema::table('patients', function (Blueprint $table) {
            // Add gender as enum; nullable to be safe for existing records
            if (! Schema::hasColumn('patients', 'gender')) {
                $table->enum('gender', ['Male', 'Female'])->nullable()->after('date_of_birth');
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
        Schema::table('patients', function (Blueprint $table) {
            if (Schema::hasColumn('patients', 'gender')) {
                $table->dropColumn('gender');
            }
        });
    }
};
