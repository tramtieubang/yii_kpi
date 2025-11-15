<?php
namespace common\helpers;

class StringHelper
{
    /**
     * Cắt chuỗi + thêm "..."
     */
    public static function truncate($string, $length = 50, $suffix = '...')
    {
        return mb_strlen($string) > $length 
            ? mb_substr($string, 0, $length) . $suffix 
            : $string;
    }

    /**
     * Tạo slug từ chuỗi
     */
    public static function slugify($string)
    {
        $string = strtolower(trim($string));
        $string = preg_replace('/[^a-z0-9-]+/u', '-', $string);
        $string = preg_replace('/-+/', '-', $string);
        return trim($string, '-');
    }

    /**
     * Viết hoa chữ cái đầu
     */
    public static function capitalize($string)
    {
        return mb_strtoupper(mb_substr($string, 0, 1)) . mb_substr($string, 1);
    }
}
