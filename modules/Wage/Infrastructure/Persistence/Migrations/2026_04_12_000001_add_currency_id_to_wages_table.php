<?php
// modules/Wage/Infrastructure/Persistence/Migrations/2026_04_12_000001_add_currency_id_to_wages_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wages', function (Blueprint $table): void {
            $table->uuid('currency_id')->nullable()->after('amount');
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('wages', function (Blueprint $table): void {
            $table->dropForeign(['currency_id']);
            $table->dropColumn('currency_id');
        });
    }
};
