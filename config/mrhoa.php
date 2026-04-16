<?php

/**
 * Giao diện / logic shop MrHoa (hoa tươi).
 *
 * MRHOA_HOME_NEW_CATEGORY_IDS: danh sách ID category, cách nhau bởi dấu phẩy (vd. 12,15,20).
 * Dùng khi chưa lưu base.home_new_products_category_ids trong cài đặt hệ thống.
 */
return [
    'home_new_products_category_ids' => array_values(array_filter(array_map(
        'intval',
        array_filter(explode(',', (string) env('MRHOA_HOME_NEW_CATEGORY_IDS', '')))
    ))),

    /**
     * Ẩn mục menu header theo tên (đã chuẩn hóa, so khớp không phân biệt hoa thường).
     * Ví dụ bỏ "Liên hệ" trùng với khối liên hệ ở chân trang.
     */
    'hide_header_menu_names' => [
        'liên hệ',
        'lien he',
    ],

    /**
     * true: sidebar lọc hiện mọi giá trị thuộc tính đã tạo (nhóm Tag bộ lọc), không chỉ giá trị đang có trên SP trong danh mục.
     * false: hành vi cũ — chỉ tag xuất hiện khi ít nhất một SP trong danh mục mang giá trị đó.
     * Env: MRHOA_FILTER_FULL_TAGS=true|false
     */
    'category_filter_full_tag_values' => filter_var(
        env('MRHOA_FILTER_FULL_TAGS', 'true'),
        FILTER_VALIDATE_BOOLEAN
    ),

    /**
     * true: trên URL /categories/{id}?attr=... danh sách SP lọc theo tag trên toàn cửa hàng (không kẹt trong danh mục đó).
     * false: hành vi gốc — chỉ SP thuộc danh mục (và con) khớp tag.
     * Env: MRHOA_CATEGORY_ATTR_PRODUCTS_GLOBAL=true|false
     */
    'category_attr_products_global' => filter_var(
        env('MRHOA_CATEGORY_ATTR_PRODUCTS_GLOBAL', 'true'),
        FILTER_VALIDATE_BOOLEAN
    ),

    /**
     * Ẩn cột hoặc dòng link chân trang (so khớp tên cột hoặc text link, không phân biệt hoa thường, có/không dấu).
     */
    'hide_footer_labels' => [
        'hỗ trợ',
        'ho tro',
        'thương hiệu',
        'thuong hieu',
        'tài khoản',
        'tai khoan',
        'đơn hàng',
        'don hang',
    ],
];
