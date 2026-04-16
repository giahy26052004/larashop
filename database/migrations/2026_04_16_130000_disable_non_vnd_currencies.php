<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Chỉ dùng VND — tắt mọi loại tiền khác (không xóa bản ghi để tránh lỗi tham chiếu cũ).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! DB::getSchemaBuilder()->hasTable('currencies')) {
            return;
        }

        $now = now();

        DB::table('currencies')->where('code', '!=', 'VND')->update([
            'status'     => 0,
            'updated_at' => $now,
        ]);

        DB::table('currencies')->where('code', 'VND')->update([
            'status'     => 1,
            'value'      => 1,
            'updated_at' => $now,
        ]);
    }

    public function down(): void
    {
        if (! DB::getSchemaBuilder()->hasTable('currencies')) {
            return;
        }

        DB::table('currencies')->whereIn('code', ['USD', 'EUR', 'CNY'])->update([
            'status'     => 1,
            'updated_at' => now(),
        ]);
    }
};
