<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Use fixed flat shipping (30_000 VND) instead of percent, so checkout totals match cart estimate.
     */
    public function up(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        DB::table('settings')
            ->where('space', 'flat_shipping')
            ->where('name', 'type')
            ->update(['value' => 'fixed']);

        DB::table('settings')
            ->where('space', 'flat_shipping')
            ->where('name', 'value')
            ->update(['value' => '30000']);
    }

    public function down(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        DB::table('settings')
            ->where('space', 'flat_shipping')
            ->where('name', 'type')
            ->update(['value' => 'percent']);

        DB::table('settings')
            ->where('space', 'flat_shipping')
            ->where('name', 'value')
            ->update(['value' => '10']);
    }
};
