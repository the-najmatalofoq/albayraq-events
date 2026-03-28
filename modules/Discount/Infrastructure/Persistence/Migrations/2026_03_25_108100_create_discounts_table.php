<?php
// modules/Discount/Infrastructure/Persistence/Migrations/2026_03_25_108100_create_discounts_table.php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('discountable_id');
            $table->string('discountable_type');
            $table->decimal('amount', 10, 2);
            $table->string('reason');
            $table->timestamps();

            $table->index(['discountable_id', 'discountable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
