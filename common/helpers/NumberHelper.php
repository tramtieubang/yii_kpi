<?php
namespace app\common\helpers;

class NumberHelper
{
    /**
     * Format tiền tệ VNĐ
     * 1250000 => 1.250.000 ₫
     */
    public static function formatCurrency($number, $suffix = ' ₫')
    {
        return number_format($number, 0, ',', '.') . $suffix;
    }

    /**
     * Format số có dấu phân cách hàng nghìn
     */
    public static function formatNumber($number, $decimals = 0)
    {
        return number_format($number, $decimals, ',', '.');
    }

    /**
     * Chuyển chữ số sang % (ví dụ 0.85 -> 85%)
     */
    public static function toPercent($value, $decimals = 0)
    {
        return number_format($value * 100, $decimals) . '%';
    }

    /**
     * Chuyển chuỗi tiền tệ VNĐ sang số
     * Ví dụ: "1.250.000 ₫" => 1250000
     */
    public static function parseCurrency($string)
    {
        if (empty($string)) {
            return 0;
        }

        // Bỏ ký tự không phải số (0-9) và dấu phẩy, dấu chấm
        $number = preg_replace('/[^0-9,.-]/', '', $string);

        // Nếu có dấu phẩy dùng cho phần thập phân (ví dụ: 1.250,50)
        if (strpos($number, ',') !== false && strpos($number, '.') !== false) {
            // Xóa dấu chấm (ngăn cách nghìn), thay dấu phẩy bằng dấu chấm
            $number = str_replace('.', '', $number);
            $number = str_replace(',', '.', $number);
        } else {
            // Chỉ xóa dấu chấm và phẩy
            $number = str_replace([',', '.'], '', $number);
        }

        return (int)$number;
    }

    /**
     * Định dạng số kiểu Việt Nam
     * Ví dụ: 2000000.12 => 2.000.000,12
     */
    public static function formatNumberVN($number, $decimals = 2)
    {
        if ($number === null || $number === '') {
            return '';
        }
        return number_format((float)$number, $decimals, ',', '.');
    }

    /**
     * Chuyển chuỗi số kiểu Việt Nam về dạng float
     * Ví dụ: 2.000.000,12 => 2000000.12
     */
    public static function parseNumberVN($str)
    {
        if ($str === null || $str === '') {
            return 0;
        }

        // Bỏ dấu chấm ngăn cách nghìn
        $str = str_replace('.', '', $str);
        // Đổi dấu phẩy thành dấu chấm ngăn cách thập phân
        $str = str_replace(',', '.', $str);

        return (float)$str;
    }

}
