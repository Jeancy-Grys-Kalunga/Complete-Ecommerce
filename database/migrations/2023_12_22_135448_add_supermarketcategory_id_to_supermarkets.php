<?php

use App\Models\SuperMarketCategory;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('supermarkets', function (Blueprint $table) {
            $table->foreignIdFor(SuperMarketCategory::class)->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supermarkets', function (Blueprint $table) {
            $table->dropColumn(['super_market_category_id']);
        });
    }
};
