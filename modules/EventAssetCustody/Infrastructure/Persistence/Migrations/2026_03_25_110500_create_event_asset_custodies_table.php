<?php
// modules/EventAssetCustody/Infrastructure/Persistence/Migrations/2026_03_25_110500_create_event_asset_custodies_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_asset_custodies', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('event_id')->constrained('events')->cascadeOnDelete();
            $table->foreignUuid('event_participation_id')->constrained('event_participations')->restrictOnDelete();
            $table->json('item_name');
            $table->json('description')->nullable();
            $table->timestamp('handed_at');
            $table->timestamp('returned_at')->nullable();
            $table->string('status')->default('handed');
            $table->foreignUuid('handed_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_asset_custodies');
    }
};
