<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// fix: we are in the develoment env for now, so, we can update the origina migration file of the users table to add the national_id and remove this file
return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'national_id')) {
                $table->string('national_id')->nullable()->after('phone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'national_id')) {
                $table->dropColumn('national_id');
            }
        });
    }
};
