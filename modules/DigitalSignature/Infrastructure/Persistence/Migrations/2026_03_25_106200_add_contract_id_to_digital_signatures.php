<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('digital_signatures', function (Blueprint $table): void {
            if (!Schema::hasColumn('digital_signatures', 'contract_id')) {
                $table->foreignUuid('contract_id')->constrained('event_contracts')->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('digital_signatures', function (Blueprint $table): void {
            $table->dropForeign(['contract_id']);
            $table->dropColumn('contract_id');
        });
    }
};
