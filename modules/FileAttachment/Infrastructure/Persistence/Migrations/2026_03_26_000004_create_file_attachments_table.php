<?php
// modules/FileAttachment/Infrastructure/Persistence/Migrations/2026_03_26_000004_create_file_attachments_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('file_attachments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('original_name');
            $table->string('storage_path');
            $table->string('mime_type');
            $table->bigInteger('size');
            $table->foreignUuid('uploader_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_attachments');
    }
};
