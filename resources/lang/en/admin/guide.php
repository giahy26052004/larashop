<?php

/**
 * order.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2022-08-02 14:22:41
 * @modified   2022-08-02 14:22:41
 */

return [
    // Heading
    'heading_title' => 'Hướng dẫn khởi tạo',

    //Tab
    'tab_basic'            => 'Cài đặt cơ bản',
    'tab_language'         => 'Đa ngôn ngữ và tiền tệ',
    'tab_product'          => 'Tạo sản phẩm',
    'tab_theme'            => 'Trang trí cửa hàng',
    'tab_payment_shipping' => 'Thanh toán và vận chuyển',
    'tab_mail'             => 'Cấu hình mail',

    //Text
    'text_extension'  => 'Tiện ích mở rộng',
    'text_success'    => 'Thành công: Hướng dẫn cho người mới đã được cập nhật!',
    'text_edit'       => 'Chỉnh sửa hướng dẫn cho người mới',
    'text_view'       => 'Xem chi tiết...',
    'text_greeting'   => 'Chúc mừng, website của bạn đã cài đặt thành công BeikeShop!',
    'text_greeting_1' => 'Chúng tôi sẽ hướng dẫn bạn cấu hình một số thiết lập cơ bản để giúp bạn làm quen với hệ thống BeikeShop và bắt đầu sử dụng nhanh chóng!',
    'text_basic_1'    => 'Trước tiên, bạn có thể cấu hình các thông tin quan trọng sau trong cài đặt hệ thống:',
    'text_language_1' => 'Hệ thống BeikeShop hỗ trợ nhiều ngôn ngữ và tiền tệ. Trước khi tạo sản phẩm đầu tiên, bạn có thể chọn ngôn ngữ và tiền tệ mặc định cho cửa hàng!',
    'text_language_2' => 'Nếu bạn chỉ cần sử dụng một ngôn ngữ và tiền tệ, bạn có thể xóa các ngôn ngữ và tiền tệ khác để tránh phải nhập thông tin nhiều lần khi tạo sản phẩm.',
    'text_product_1'  => 'Trong quá trình cài đặt hệ thống, một số dữ liệu sản phẩm mẫu sẽ được nhập sẵn để demo. Bạn có thể thử <a href="' . admin_route('products.create') . '">tạo sản phẩm</a> trước!',
    'text_product_2'  => 'BeikeShop cung cấp chức năng quản lý sản phẩm mạnh mẽ! Bao gồm: <a href="' . admin_route('categories.index') . '">Phân loại sản phẩm</a>, <a href="' . admin_route('brands.index') . '">Quản lý thương hiệu</a>, sản phẩm đa thuộc tính, <a href="' . admin_route('multi_filter.index') . '">Lọc nâng cao</a>, <a href="' . admin_route('attributes.index') . '">Thuộc tính sản phẩm</a> và nhiều chức năng khác.',
    'text_theme_1'    => 'Hệ thống đã cài đặt sẵn một bộ giao diện mặc định. Nếu giao diện hiện tại không phù hợp, bạn có thể dùng <a href="' . admin_route('marketing.index', ['type' => ' theme']) . '">Chợ plugin</a> để mua thêm giao diện khác.',
    'text_theme_2'    => 'Ngoài ra, trang chủ của giao diện front-end được xây dựng bởi module thông qua layout. Bạn có thể cần điều chỉnh một số cài đặt module trong layout.',
    'text_theme_3'    => 'Nếu bạn mua APP, chúng tôi còn cung cấp tính năng <a href="' . admin_route('design_app_home.index') . '">thiết kế trang chủ APP</a>.',
    'text_payment_1'  => 'BeikeShop hỗ trợ các cổng thanh toán quốc tế phổ biến như PayPal, Stripe... Trước khi nhận đơn chính thức, bạn cần bật và cấu hình phương thức thanh toán tương ứng.',
    'text_payment_2'  => 'Lưu ý: Một số cổng thanh toán cần thời gian xét duyệt, hãy đăng ký trước. Các phương thức thanh toán dùng ở Trung Quốc có thể yêu cầu đăng ký tên miền.',
    'text_payment_3'  => 'Ngoài ra, bạn cũng cần thiết lập phương thức vận chuyển để khách hàng lựa chọn. Hệ thống cung cấp plugin phí vận chuyển cố định miễn phí.',
    'text_payment_4'  => 'Bạn cũng có thể vào BeikeShop <a href="' . admin_route('marketing.index') . '">"Chợ plugin"</a> để tìm hiểu và tải thêm phương thức thanh toán, vận chuyển!',
    'text_mail_1'     => 'Thông báo email giúp khách hàng cập nhật trạng thái đơn hàng, đồng thời cho phép đăng ký và lấy lại mật khẩu qua email.',
    'text_mail_2'     => 'Bạn có thể cấu hình SMTP theo nhu cầu kinh doanh, và dùng engine mail như Sendmail để gửi email.',

    // Button
    'button_setting_general' => 'Cài đặt cơ bản website',
    'button_setting_store'   => 'Tên cửa hàng',
    'button_setting_logo'    => 'Đổi logo',
    'button_setting_option'  => 'Cài đặt tùy chọn',
    'button_setting'         => 'Tất cả cài đặt hệ thống',
    'button_language'        => 'Quản lý ngôn ngữ',
    'button_currency'        => 'Quản lý tiền tệ',
    'button_product'         => 'Xem sản phẩm',
    'button_product_create'  => 'Tạo sản phẩm',
    'button_theme_pc'        => 'Cài đặt giao diện máy tính',
    'button_theme_h5'        => 'Cài đặt giao diện di động',
    'button_theme'           => 'Tất cả giao diện',
    'button_layout'          => 'Quản lý layout',
    'button_payment'         => 'Phương thức thanh toán',
    'button_shipping'        => 'Phương thức vận chuyển',
    'button_mail'            => 'Cài đặt mail',
    'button_sms'             => 'Cấu hình SMS',
    'button_hide'            => 'Không hiển thị nữa',
];
