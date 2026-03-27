<?php
// modules/Event/Infrastructure/Persistence/Migrations/2026_03_26_000006_create_events_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->json('name');
            $table->string('slug')->unique();
            $table->json('description');
            $table->string('type');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->bigInteger('price_amount');
            $table->string('price_currency', 3)->default('SAR');
            $table->string('status');
            $table->uuid('banner_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreignUuid('banner_id')->nullable()->constrained('file_attachments')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
