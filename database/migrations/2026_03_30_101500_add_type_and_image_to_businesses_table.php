<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('businesses', 'businesses_type_id')) {
            Schema::table('businesses', function (Blueprint $table) {
                $table->foreignId('businesses_type_id')
                    ->nullable()
                    ->after('phone')
                    ->constrained('businesses_types')
                    ->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('businesses', 'image')) {
            Schema::table('businesses', function (Blueprint $table) {
                $table->string('image')->nullable()->after('businesses_type_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('businesses', 'image')) {
            Schema::table('businesses', function (Blueprint $table) {
                $table->dropColumn('image');
            });
        }

        if (Schema::hasColumn('businesses', 'businesses_type_id')) {
            Schema::table('businesses', function (Blueprint $table) {
                $table->dropConstrainedForeignId('businesses_type_id');
            });
        }
    }
};
