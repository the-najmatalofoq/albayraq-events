<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('violation_types', function (Blueprint $table) {
            $table->dropColumn([
                'default_deduction_amount',
                'default_deduction_currency',
                'event_id',
                'severity',
            ]);
            $table->string('slug')->unique()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('violation_types', function (Blueprint $table) {
            $table->decimal('default_deduction_amount', 12, 2)->nullable();
            $table->string('default_deduction_currency', 3)->nullable();
            $table->uuid('event_id')->nullable();
            $table->dropColumn('slug');
        });
    }
};
