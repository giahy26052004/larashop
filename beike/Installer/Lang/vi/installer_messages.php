<?php

return [

    /*
     *
     * Shared translations.
     *
     */
    'title'        => 'Trình cài đặt Laravel',
    'next'         => 'Bước tiếp theo',
    'back'         => 'Quay lại',
    'finish'       => 'Cài đặt',
    'status'       => 'Trạng thái',
    'forms'        => [
        'errorTitle' => 'Các lỗi sau đã xảy ra:',
    ],

    /*
     *
     * Home page translations.
     *
     */
    'welcome'      => [
        'template_title'   => 'Chào mừng',
        'title'            => 'Chào mừng đến với trình hướng dẫn cài đặt',
        'describe'         => 'Chào mừng bạn đến với BeikeShop. Cài đặt và thiết lập dễ dàng.',
        'message'          => 'Trình hướng dẫn cài đặt và thiết lập.',
        'next'             => 'Kiểm tra yêu cầu',
        'copyright_title'  => 'Thông tin bản quyền',
        'copyright_btn'    => 'Đã đọc và đồng ý',
        'copyright_list_1' => '1. Bản quyền hệ thống này thuộc về Công ty TNHH Công nghệ mạng Thành Đô Quang Đại.',
        'copyright_list_2' => '2. Trừ khi có sự cho phép bằng văn bản của chúng tôi, cá nhân, đơn vị hoặc tổ chức không được quyền bán hoặc cho thuê hệ thống này và các sản phẩm phái sinh của nó để kiếm lợi.',
        'copyright_list_3' => '3. Xin giữ lại thông tin bản quyền của chúng tôi. Nếu muốn xóa cần được cấp phép bởi chúng tôi.',
        'statement_1'      => 'Tuyên bố miễn trừ trách nhiệm:',
        'statement_2'      => 'Tuyên bố rủi ro: Việc sử dụng và cài đặt hệ thống BeikeShop hoàn toàn dựa trên quyết định của bạn. Chúng tôi không chịu trách nhiệm về bất kỳ tổn thất, thiệt hại hoặc trách nhiệm pháp lý nào phát sinh từ việc sử dụng hệ thống này.',
        'statement_3'      => 'Tuân thủ pháp luật: Khi sử dụng hệ thống này, bạn đồng ý không tham gia các hoạt động kinh doanh trái pháp luật, xâm phạm bản quyền hoặc vi phạm quy định địa phương. Chúng tôi không chịu trách nhiệm cho hành vi kinh doanh của bạn.',
        'statement_4'      => 'Mất dữ liệu và tấn công hacker: Mặc dù chúng tôi đã áp dụng các biện pháp bảo mật hợp lý để bảo vệ hệ thống, vẫn tồn tại rủi ro mất dữ liệu và tấn công hacker. Chúng tôi không chịu trách nhiệm nếu hệ thống xảy ra mất dữ liệu, tấn công hacker hoặc các sự kiện an ninh khác. Bạn tự chịu trách nhiệm bảo vệ dữ liệu và an toàn hệ thống.',
    ],

    /*
     *
     * Requirements page translations.
     *
     */
    'requirements' => [
        'template_title' => 'Bước 1 | Yêu cầu máy chủ',
        'title'          => 'Kiểm tra yêu cầu hệ thống',
        'environment'    => 'Môi trường',
        'next'           => 'Kiểm tra quyền',
    ],

    /*
     *
     * Permissions page translations.
     *
     */
    'permissions'  => [
        'template_title' => 'Bước 2 | Quyền thư mục',
        'title'          => 'Kiểm tra quyền thư mục',
        'next'           => 'Cấu hình tham số môi trường',
        'table'          => 'Thư mục',
    ],

    /*
    *
    * Environment page translations.
    *
    */
    'environment'  => [
        'template_title'                       => 'Bước 3 | Tham số hệ thống',
        'title'                                => 'Cấu hình tham số hệ thống',
        'name_required'                        => 'Cần nhập tên môi trường.',
        'database_link'                        => 'Liên kết cơ sở dữ liệu',
        'admin_info'                           => 'Thiết lập tài khoản và mật khẩu quản trị',
        'app_name_label'                       => 'Tên ứng dụng',
        'app_name_placeholder'                 => 'Tên ứng dụng',
        'app_environment_label'                => 'Môi trường ứng dụng',
        'db_connection_failed_host_port'       => 'Lỗi máy chủ hoặc cổng cơ sở dữ liệu!',
        'db_connection_failed_user_password'   => 'Lỗi tên đăng nhập hoặc mật khẩu cơ sở dữ liệu!',
        'db_connection_failed_database_name'   => 'Tên cơ sở dữ liệu không tồn tại hoặc bị sai!',
        'db_connection_failed_invalid_version' => 'Phiên bản MySQL phải lớn hơn 5.7!',
        'app_environment_label_local'          => 'Local',
        'app_environment_label_developement'   => 'Phát triển',
        'app_environment_label_qa'             => 'QA',
        'app_environment_label_production'     => 'Sản xuất',
        'app_environment_label_other'          => 'Khác',
        'app_environment_placeholder_other'    => 'Nhập môi trường...',
        'app_url_label'                        => 'URL ứng dụng',
        'app_url_placeholder'                  => 'URL ứng dụng',
        'db_connection_failed'                 => 'Không thể kết nối đến cơ sở dữ liệu.',
        'db_connection_label'                  => 'Kết nối cơ sở dữ liệu',
        'db_connection_label_mysql'            => 'MySQL',
        'db_connection_label_sqlite'           => 'SQLite',
        'db_connection_label_pgsql'            => 'PostgreSQL',
        'db_connection_label_sqlsrv'           => 'SQL Server',
        'db_host_label'                        => 'Host cơ sở dữ liệu',
        'db_host_placeholder'                  => 'Host cơ sở dữ liệu',
        'db_port_label'                        => 'Cổng cơ sở dữ liệu',
        'db_port_placeholder'                  => 'Cổng cơ sở dữ liệu',
        'db_name_label'                        => 'Tên cơ sở dữ liệu',
        'db_name_placeholder'                  => 'Tên cơ sở dữ liệu',
        'db_username_label'                    => 'Tên đăng nhập cơ sở dữ liệu',
        'db_username_placeholder'              => 'Tên đăng nhập cơ sở dữ liệu',
        'db_password_label'                    => 'Mật khẩu cơ sở dữ liệu',
        'db_password_placeholder'              => 'Mật khẩu cơ sở dữ liệu',
        'admin_email'                          => 'Tài khoản quản trị',
        'admin_password'                       => 'Mật khẩu quản trị',
        'install'                              => 'Cài đặt',
        'ajax_database_parameters'             => 'Đang kiểm tra tham số cơ sở dữ liệu...',
        'ajax_database_success'                => 'Kết nối cơ sở dữ liệu thành công',
        'error_email'                          => 'Vui lòng nhập địa chỉ email hợp lệ',
        'table_already_exists'                 => 'Phát hiện bảng dữ liệu đã tồn tại trong cơ sở dữ liệu, vui lòng xóa hoặc sao lưu trước khi cài đặt!',
        'php_extension'                        => 'Vui lòng cài đặt các phần mở rộng PHP cần thiết',
        'down_phpversion'                      => 'Vui lòng hạ phiên bản PHP xuống PHP 8.1 hoặc 8.2',
    ],

    /*
     *
     * Installed Log translations.
     *
     */
    'installed'    => [
        'success_log_message' => 'Laravel Installer đã cài đặt thành công vào ',
    ],

    /*
     *
     * Final page translations.
     *
     */
    'final'        => [
        'title'          => 'Cài đặt hoàn tất',
        'template_title' => 'Cài đặt hoàn tất',
        'migration'      => 'Đầu ra Migration & Seed:',
        'console'        => 'Đầu ra Console Ứng dụng:',
        'log'            => 'Nhật ký cài đặt:',
        'env'            => 'Tệp .env cuối cùng:',
        'exit'           => 'Nhấn vào đây để thoát',
        'finished'       => 'Chúc mừng bạn, hệ thống đã cài đặt thành công, hãy trải nghiệm ngay',
        'to_front'       => 'Truy cập trang chủ',
        'to_admin'       => 'Truy cập quản trị',
        'nginx_alert'    => 'Máy chủ Nginx cần cấu hình quy tắc giả tĩnh, nhấn <a href="https://docs.beikeshop.com/install/source.html#nginx" target="_blank">xem hướng dẫn cấu hình</a>',
    ],

    /*
     *
     * Update specific translations
     *
     */
    'updater'      => [
        'title'    => 'Trình cập nhật Laravel',
        'welcome'  => [
            'title'   => 'Chào mừng đến Trình cập nhật',
            'message' => 'Chào mừng bạn đến với trình hướng dẫn cập nhật.',
        ],
        'overview' => [
            'title'           => 'Tổng quan',
            'message'         => 'Có 1 bản cập nhật.|Có :number bản cập nhật.',
            'install_updates' => 'Cài đặt cập nhật',
        ],
        'final'    => [
            'title'    => 'Hoàn tất',
            'finished' => 'Cơ sở dữ liệu ứng dụng đã được cập nhật thành công.',
            'exit'     => 'Nhấn vào đây để thoát',
        ],
        'log'      => [
            'success_message' => 'Laravel Installer đã cập nhật thành công vào ',
        ],
    ],
];
