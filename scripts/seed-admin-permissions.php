<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Beike\Models\AdminUser;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

$guard = 'web_admin';

$codes = [
    'orders_index', 'orders_export', 'orders_show', 'orders_update_status', 'orders_delete', 'orders_trashed', 'orders_restore',
    'rmas_index', 'rmas_show', 'rmas_update', 'rmas_delete',
    'rma_reasons_index', 'rma_reasons_create', 'rma_reasons_update', 'rma_reasons_delete',
    'products_index', 'products_create', 'products_show', 'products_update', 'products_delete', 'products_trashed', 'products_restore', 'products_filter_index', 'products_filter_update',
    'categories_index', 'categories_create', 'categories_show', 'categories_update', 'categories_delete',
    'brands_index', 'brands_create', 'brands_show', 'brands_update', 'brands_delete',
    'attributes_index', 'attributes_create', 'attributes_show', 'attributes_update', 'attributes_delete',
    'attribute_groups_index', 'attribute_groups_create', 'attribute_groups_update', 'attribute_groups_delete',
    'customers_index', 'customers_create', 'customers_show', 'customers_update', 'customers_delete',
    'customer_groups_index', 'customer_groups_create', 'customer_groups_show', 'customer_groups_update', 'customer_groups_delete',
    'pages_index', 'pages_create', 'pages_show', 'pages_update', 'pages_delete',
    'page_categories_index', 'page_categories_create', 'page_categories_show', 'page_categories_update', 'page_categories_delete',
    'settings_index', 'settings_update',
    'theme_index', 'theme_update',
    'design_menu_index', 'design_menu_update',
    'design_index', 'design_update',
    'design_footer_index', 'design_footer_update',
    'design_app_home_index', 'design_app_home_update', 'app_push_index',
    'plugins_index', 'plugins_import', 'plugins_update', 'plugins_show', 'plugins_install', 'plugins_update_status', 'plugins_uninstall',
    'marketing_index', 'marketing_show', 'marketing_buy', 'marketing_download',
    'reports_sale', 'reports_view',
    'admin_users_index', 'admin_users_create', 'admin_users_show', 'admin_users_update', 'admin_users_delete',
    'admin_roles_index', 'admin_roles_create', 'admin_roles_show', 'admin_roles_update', 'admin_roles_delete',
    'regions_index', 'regions_create', 'regions_show', 'regions_update', 'regions_delete',
    'tax_rates_index', 'tax_rates_create', 'tax_rates_show', 'tax_rates_update', 'tax_rates_delete',
    'tax_classes_index', 'tax_classes_create', 'tax_classes_show', 'tax_classes_update', 'tax_classes_delete',
    'currencies_index', 'currencies_create', 'currencies_show', 'currencies_update', 'currencies_delete',
    'languages_index', 'languages_create', 'languages_update', 'languages_delete',
    'file_manager_create', 'file_manager_show', 'file_manager_update', 'file_manager_delete',
    'zones_create', 'zones_index', 'zones_update', 'zones_delete',
    'countries_create', 'countries_index', 'countries_update', 'countries_delete',
    'help_index',
    'account_index', 'account_update',
];

foreach ($codes as $code) {
    Permission::findOrCreate($code, $guard);
}

$role = Role::findOrCreate('admin', $guard);
$role->syncPermissions($codes);

$user = AdminUser::where('email', 'admin@local.com')->first();
if ($user) {
    $user->syncRoles([$role]);
    echo "Assigned admin role to user: {$user->email}\n";
} else {
    echo "Admin user not found: admin@local.com\n";
}

echo "Admin permissions seeded and role synced.\n";
