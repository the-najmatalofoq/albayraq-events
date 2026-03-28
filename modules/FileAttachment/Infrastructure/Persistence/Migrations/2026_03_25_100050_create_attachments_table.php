<?php
// modules/FileAttachment/Infrastructure/Persistence/Migrations/2026_03_25_100050_create_attachments_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attachments', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('attachable_id');
            $table->string('attachable_type');
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_type'); // image, pdf, etc.
            $table->unsignedBigInteger('file_size');
            $table->string('collection')->default('default'); // avatar, contract, identity
            $table->timestamps();

            $table->index(['attachable_id', 'attachable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
