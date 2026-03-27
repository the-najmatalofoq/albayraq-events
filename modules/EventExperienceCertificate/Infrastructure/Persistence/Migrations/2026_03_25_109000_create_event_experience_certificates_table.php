<?php
// modules/EventExperienceCertificate/Infrastructure/Persistence/Migrations/2026_03_25_109000_create_event_experience_certificates_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_experience_certificates', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('event_participation_id')->unique()->constrained('event_participations')->cascadeOnDelete();
            $table->decimal('total_hours', 8, 2);
            $table->decimal('average_score', 3, 1);
            $table->timestamp('issued_at');
            $table->string('verification_code')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_experience_certificates');
    }
};
