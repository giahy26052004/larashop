<?php
/**
 * CurrenciesSeeder.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2022-09-05 19:42:42
 * @modified   2022-09-05 19:42:42
 */

namespace Database\Seeders;

use Beike\Models\Currency;
use Illuminate\Database\Seeder;

class CurrenciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = $this->getItems();

        if ($items) {
            Currency::query()->truncate();
            foreach ($items as $item) {
                Currency::query()->create($item);
            }
        }
    }


    public function getItems()
    {
        // Chỉ VND — shop không bật loại tiền khác (có thể thêm tay trong admin nếu cần).
        return [
            [
                'id'              => 1,
                'name'            => 'Việt Nam Đồng',
                'code'            => 'VND',
                'symbol_left'     => '',
                'symbol_right'    => 'đ',
                'decimal_place'   => 0,
                'value'           => 1,
                'status'          => 1,
            ],
        ];
    }
}
