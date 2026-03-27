<?php
// modules/EventAssetCustody/Infrastructure/Persistence/Migrations/2026_03_26_000015_create_event_asset_custodies_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_asset_custodies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('event_participation_id')->constrained('event_participations')->cascadeOnDelete();
            $table->json('item_name');
            $table->json('description')->nullable();
            $table->timestamp('handed_at')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->string('status');
            $table->uuid('handed_by');
            $table->timestamps();

            $table->foreign('handed_by')->references('id')->on('users')->cascadeOnDelete();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_asset_custodies');
    }
};
