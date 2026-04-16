<?php

namespace Beike\Shop\Http\Controllers;

use Beike\Repositories\ProductRepo;
use Beike\Services\DesignService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * 通过page builder 显示首页
     *
     * @return View
     * @throws \Exception
     */
    public function index(): mixed
    {
        $originalUri = session()->get('originalUri');
        if ($originalUri === '/') {
            if (locale() !== system_setting('base.locale')) {
                return $this->redirect();
            }
        }

        $designSettings = system_setting('base.design_setting');
        $modules        = $designSettings['modules'] ?? [];

        $allowedCodes = ['slideshow', 'img_text_banner', 'product', 'tab_product', 'latest'];
        $modules       = array_filter($modules, function ($module) use ($allowedCodes) {
            return in_array($module['code'] ?? '', $allowedCodes, true);
        });

        $moduleItems = [];
        foreach ($modules as $module) {
            $code       = $module['code'];
            $moduleId   = $module['module_id'] ?? '';
            $content    = $module['content'];
            $viewPath   = $module['view_path'] ?? '';

            if ($viewPath) {
                $plugin = plugin(Str::before($viewPath, '::'));

                if ($plugin && $plugin->type == 'theme' && $plugin->code != system_setting('base.theme')) {
                    continue;
                }
            }

            if (empty($viewPath)) {
                $viewPath = "design.{$code}";
            }

            $paths = explode('::', $viewPath);
            if (count($paths) == 2) {
                $pluginCode = $paths[0];
                if (! app('plugin')->checkActive($pluginCode)) {
                    continue;
                }
            }

            if (view()->exists($viewPath) && $moduleId) {
                $moduleItems[] = [
                    'code'      => $code,
                    'module_id' => $moduleId,
                    'view_path' => $viewPath,
                    'content'   => DesignService::handleModuleContent($code, $content),
                ];
            }
        }

        if (empty($moduleItems)) {
            $moduleItems = $this->buildMinimalHomeModules();
        }

        $data = ['modules' => $moduleItems];

        $data = hook_filter('home.index.data', $data);

        return view('home', $data);
    }

    /**
     * Fallback khi chưa cấu hình module trang chủ trong admin.
     * Sản phẩm lấy theo danh mục (nếu cấu hình), không thì mới nhất toàn shop — xem resolveHomeNewProductCategoryIds().
     */
    private function buildMinimalHomeModules(): array
    {
        $limit        = (int) system_setting('base.home_new_products_limit', 8);
        $limit        = $limit > 0 ? $limit : 8;
        $categoryIds  = $this->resolveHomeNewProductCategoryIds();

        $filters = [
            'active' => 1,
            'sort'   => 'created_at',
            'order'  => 'desc',
        ];
        if ($categoryIds !== []) {
            $filters['category_id'] = $categoryIds;
        }

        $productIds = ProductRepo::getBuilder($filters)
            ->whereHas('masterSku')
            ->limit($limit)
            ->pluck('products.id')
            ->unique()
            ->values()
            ->toArray();

        if (! $productIds) {
            return [];
        }

        return [
            [
                'code'      => 'product',
                'module_id' => 'minimal-home-products',
                'view_path' => 'design.product',
                'content'   => [
                    'module_size' => 'container-fluid',
                    'title'       => 'Hoa mới',
                    'products'    => ProductRepo::getProductsByIds($productIds)->jsonSerialize(),
                ],
            ],
        ];
    }

    /**
     * ID danh mục dùng cho block "Hoa mới" (fallback): ưu tiên cài đặt hệ thống, sau đó env MRHOA_HOME_NEW_CATEGORY_IDS.
     * Rỗng = không lọc category (mới nhất toàn cửa hàng).
     *
     * @return int[]
     */
    private function resolveHomeNewProductCategoryIds(): array
    {
        $raw = system_setting('base.home_new_products_category_ids', config('mrhoa.home_new_products_category_ids', []));

        return $this->normalizeIdList($raw);
    }

    /**
     * @param  mixed  $raw  mảng ID, JSON "[1,2]", hoặc chuỗi "1,2,3"
     * @return int[]
     */
    private function normalizeIdList(mixed $raw): array
    {
        if ($raw === null || $raw === '') {
            return [];
        }
        if (is_array($raw)) {
            return array_values(array_unique(array_filter(array_map('intval', $raw))));
        }
        if (is_string($raw)) {
            $decoded = json_decode($raw, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return array_values(array_unique(array_filter(array_map('intval', $decoded))));
            }

            return array_values(array_unique(array_filter(array_map(
                'intval',
                preg_split('/[\s,]+/', trim($raw), -1, PREG_SPLIT_NO_EMPTY)
            ))));
        }

        return [];
    }

    private function redirect(): RedirectResponse
    {
        $lang = session()->get('locale');
        $host = request()->getSchemeAndHttpHost();
        return redirect()->to($host. '/'.$lang);
    }
}
