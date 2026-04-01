<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class CreateLocationLogsTable extends Migration
{
    public function up(): void
    {
        Schema::create('location_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('event_participation_id')->constrained('event_participations')->onDelete('cascade');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->decimal('accuracy', 5, 1)->nullable();
            $table->boolean('is_within_geofence');
            $table->timestamp('recorded_at');

            $table->index(['event_participation_id', 'recorded_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('location_logs');
    }
}
