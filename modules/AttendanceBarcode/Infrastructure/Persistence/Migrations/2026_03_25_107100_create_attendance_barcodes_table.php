<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_barcodes', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('event_participation_id')->constrained('event_participations')->cascadeOnDelete();
            $table->string('code')->unique();
            $table->timestamp('generated_at');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_barcodes');
    }
};
