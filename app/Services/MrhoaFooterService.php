<?php

namespace App\Services;

/**
 * Ẩn cột / link chân trang theo cấu hình MrHoa (không sửa DB footer).
 */
class MrhoaFooterService
{
    public static function filterFooterContent(array $footerSetting): array
    {
        $hide = config('mrhoa.hide_footer_labels', []);
        if (! is_array($hide) || $hide === []) {
            return $footerSetting;
        }

        foreach (['link1', 'link2', 'link3'] as $key) {
            if (! isset($footerSetting['content'][$key])) {
                continue;
            }
            $block = &$footerSetting['content'][$key];
            $titles = $block['title'] ?? [];
            $locale  = locale();

            $columnTitle = is_array($titles) ? (string) ($titles[$locale] ?? reset($titles) ?: '') : (string) $titles;
            if (self::labelMatches($columnTitle, $hide)) {
                if (is_array($titles)) {
                    foreach (array_keys($titles) as $loc) {
                        $block['title'][$loc] = '';
                    }
                } else {
                    $block['title'] = [];
                }
                $block['links'] = [];

                continue;
            }

            $links = $block['links'] ?? [];
            if (! is_array($links)) {
                continue;
            }
            $block['links'] = array_values(array_filter($links, function ($item) use ($hide) {
                $text = (string) ($item['text'] ?? '');

                return ! self::labelMatches($text, $hide);
            }));
        }

        return $footerSetting;
    }

    /**
     * So khớp không phân biệt hoa thường; hỗ trợ cả có dấu / không dấu (ASCII).
     */
    private static function labelMatches(string $text, array $hideList): bool
    {
        $t = mb_strtolower(trim(preg_replace('/\s+/u', ' ', $text)), 'UTF-8');
        if ($t === '') {
            return false;
        }
        $tAscii = self::asciiLower($t);

        foreach ($hideList as $raw) {
            $h = mb_strtolower(trim(preg_replace('/\s+/u', ' ', (string) $raw)), 'UTF-8');
            if ($h === '') {
                continue;
            }
            if ($t === $h || $tAscii === self::asciiLower($h)) {
                return true;
            }
        }

        return false;
    }

    private static function asciiLower(string $s): string
    {
        $map = [
            'à' => 'a', 'á' => 'a', 'ả' => 'a', 'ã' => 'a', 'ạ' => 'a', 'ă' => 'a', 'ằ' => 'a', 'ắ' => 'a', 'ẳ' => 'a', 'ẵ' => 'a', 'ặ' => 'a',
            'â' => 'a', 'ầ' => 'a', 'ấ' => 'a', 'ẩ' => 'a', 'ẫ' => 'a', 'ậ' => 'a',
            'è' => 'e', 'é' => 'e', 'ẻ' => 'e', 'ẽ' => 'e', 'ẹ' => 'e', 'ê' => 'e', 'ề' => 'e', 'ế' => 'e', 'ể' => 'e', 'ễ' => 'e', 'ệ' => 'e',
            'ì' => 'i', 'í' => 'i', 'ỉ' => 'i', 'ĩ' => 'i', 'ị' => 'i',
            'ò' => 'o', 'ó' => 'o', 'ỏ' => 'o', 'õ' => 'o', 'ọ' => 'o', 'ô' => 'o', 'ồ' => 'o', 'ố' => 'o', 'ổ' => 'o', 'ỗ' => 'o', 'ộ' => 'o',
            'ơ' => 'o', 'ờ' => 'o', 'ớ' => 'o', 'ở' => 'o', 'ỡ' => 'o', 'ợ' => 'o',
            'ù' => 'u', 'ú' => 'u', 'ủ' => 'u', 'ũ' => 'u', 'ụ' => 'u', 'ư' => 'u', 'ừ' => 'u', 'ứ' => 'u', 'ử' => 'u', 'ữ' => 'u', 'ự' => 'u',
            'ỳ' => 'y', 'ý' => 'y', 'ỷ' => 'y', 'ỹ' => 'y', 'ỵ' => 'y',
            'đ' => 'd',
        ];

        return strtr($s, $map);
    }
}
