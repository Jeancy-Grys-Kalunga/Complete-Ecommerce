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
            $table->unsignedBigInteger('super_market_category_id')->nullable();
            $table->foreign('super_market_category_id')->references('id')->on('super_market_categories')->onDelete('SET NULL');
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
