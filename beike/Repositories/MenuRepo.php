<?php

/**
 * FooterRepo.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2022-08-11 18:16:06
 * @modified   2022-08-11 18:16:06
 */

namespace Beike\Repositories;

class MenuRepo
{
    /**
     * 处理页头编辑器数据
     *
     * @return array|mixed
     * @throws \Exception
     */
    public static function handleMenuData($menuSetting = [])
    {
        if (empty($menuSetting)) {
            $menuSetting = system_setting('base.menu_setting');
        }

        $locale = locale();
        $menus  = $menuSetting['menus'] ?? [];

        foreach ($menus as $index => $menu) {
            $menuLink             = $menu['link'] ?? [];
            $menuName             = $menu['name'] ?? ($menu['title'] ?? []);
            $menuBadgeName        = $menu['badge']['name'] ?? [];
            $menu['new_window']   = $menuLink['new_window'] ?? false;
            $menu['link']         = handle_link($menuLink)['link'] ?? '';
            if (empty($menu['link']) && (($menuLink['type'] ?? '') === 'home')) {
                $menu['link'] = shop_route('home.index');
            }
            $menu['name']         = shop_ui_vietnamese(is_array($menuName) ? ($menuName[$locale] ?? '') : (string) $menuName);
            $menu['badge']['name'] = shop_ui_vietnamese(is_array($menuBadgeName) ? ($menuBadgeName[$locale] ?? '') : (string) $menuBadgeName);

            $childrenGroup = $menu['childrenGroup'] ?? [];
            if ($childrenGroup) {
                $menu['children_group'] = self::handleChildrenGroup($childrenGroup);
            }
            $menus[$index] = $menu;
        }

        $menus = self::filterHiddenHeaderMenus($menus);

        return $menus;
    }

    /**
     * @param  array<int, array>  $menus
     * @return array<int, array>
     */
    private static function filterHiddenHeaderMenus(array $menus): array
    {
        $hide = config('mrhoa.hide_header_menu_names', []);
        if (! is_array($hide) || $hide === []) {
            return $menus;
        }
        $hideLower = array_map(fn ($s) => mb_strtolower(trim((string) $s), 'UTF-8'), $hide);

        return array_values(array_filter($menus, function ($menu) use ($hideLower) {
            $n = mb_strtolower(trim((string) ($menu['name'] ?? '')), 'UTF-8');

            return $n === '' || ! in_array($n, $hideLower, true);
        }));
    }

    /**
     * 处理头部 menu 子菜单数据
     *
     * @param $childrenGroups
     * @return mixed
     * @throws \Exception
     */
    private static function handleChildrenGroup($childrenGroups)
    {
        $locale = locale();
        foreach ($childrenGroups as $groupIndex => $childrenGroup) {
            $groupName             = $childrenGroup['name'] ?? ($childrenGroup['title'] ?? []);
            $childrenGroup['name'] = shop_ui_vietnamese(is_array($groupName) ? ($groupName[$locale] ?? '') : (string) $groupName);
            if (($childrenGroup['type'] ?? '') == 'image') {
                $childrenGroup['image']['image'] = image_origin($childrenGroup['image']['image'][$locale] ?? '');
                $childrenGroup['image']['link']  = type_route($childrenGroup['image']['link']['type'] ?? '', $childrenGroup['image']['link']['value'] ?? '');
            } elseif (!empty($childrenGroup['children'])) {
                foreach (($childrenGroup['children'] ?? []) as $childrenIndex => $children) {
                    $children['link']                           = handle_link($children['link'] ?? []);
                    $childrenGroup['children'][$childrenIndex] = $children;
                }
            }
            $childrenGroups[$groupIndex] = $childrenGroup;
        }

        return $childrenGroups;
    }
}
