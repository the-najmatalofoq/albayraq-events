<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// fix: we are in the dev env, so, we can make the same objective from this migration in the existing back_details migrate file. 
return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bank_details', function (Blueprint $table) {
            // Check if unique index already exists to avoid duplication errors
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexes = $sm->listTableIndexes('bank_details');

            $hasIbanUnique = false;
            foreach ($indexes as $index) {
                if ($index->isUnique() && in_array('iban', $index->getColumns())) {
                    $hasIbanUnique = true;
                    break;
                }
            }

            if (!$hasIbanUnique) {
                $table->unique('iban');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_details', function (Blueprint $table) {
            $table->dropUnique(['iban']);
        });
    }
};
