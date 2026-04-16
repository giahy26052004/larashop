<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Đặt đồng tiền mặc định VND — giá trong DB được hiểu là số tiền VND.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! DB::getSchemaBuilder()->hasTable('currencies')) {
            return;
        }

        $now = now();

        $exists = DB::table('currencies')->where('code', 'VND')->exists();
        if ($exists) {
            DB::table('currencies')->where('code', 'VND')->update([
                'name'           => 'Việt Nam Đồng',
                'symbol_left'    => '',
                'symbol_right'   => 'đ',
                'decimal_place'  => '0',
                'value'          => 1,
                'status'         => 1,
                'updated_at'     => $now,
            ]);
        } else {
            DB::table('currencies')->insert([
                'name'           => 'Việt Nam Đồng',
                'code'           => 'VND',
                'symbol_left'    => '',
                'symbol_right'   => 'đ',
                'decimal_place'  => '0',
                'value'          => 1,
                'status'         => 1,
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);
        }

        // Đồng khác: tỷ giá so với VND (1 VND × value = hiển thị đơn vị đó) — chỉnh lại sau tại Quản trị → Tiền tệ nếu cần.
        $usdPerVnd = 1 / 24500;
        DB::table('currencies')->where('code', 'USD')->update([
            'value'      => $usdPerVnd,
            'updated_at' => $now,
        ]);
        DB::table('currencies')->where('code', 'EUR')->update([
            'value'      => $usdPerVnd * 0.92,
            'updated_at' => $now,
        ]);
        DB::table('currencies')->where('code', 'CNY')->update([
            'value'      => $usdPerVnd * 0.14,
            'updated_at' => $now,
        ]);

        if (DB::getSchemaBuilder()->hasTable('settings')) {
            DB::table('settings')
                ->where('type', 'system')
                ->where('space', 'base')
                ->where('name', 'currency')
                ->update(['value' => 'VND', 'updated_at' => $now]);
        }
    }

    public function down(): void
    {
        if (! DB::getSchemaBuilder()->hasTable('settings')) {
            return;
        }

        DB::table('settings')
            ->where('type', 'system')
            ->where('space', 'base')
            ->where('name', 'currency')
            ->update(['value' => 'USD', 'updated_at' => now()]);

        if (DB::getSchemaBuilder()->hasTable('currencies')) {
            DB::table('currencies')->where('code', 'USD')->update(['value' => 1, 'updated_at' => now()]);
        }
    }
};
