<?php
// modules/ParticipationViolation/Infrastructure/Persistence/Migrations/2026_04_13_120300_add_types_to_participation_violations_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('participation_violations', function (Blueprint $table): void {
            $table->foreignUuid('deduction_type_id')->nullable()->after('violation_type_id')->constrained('deduction_types')->nullOnDelete();
            $table->foreignUuid('penalty_type_id')->nullable()->after('deduction_type_id')->constrained('penalty_types')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('participation_violations', function (Blueprint $table): void {
            $table->dropForeign(['deduction_type_id']);
            $table->dropColumn('deduction_type_id');
            $table->dropForeign(['penalty_type_id']);
            $table->dropColumn('penalty_type_id');
        });
    }
};
