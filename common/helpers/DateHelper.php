<?php
namespace app\common\helpers;

class DateHelper
{
    /**
     * Format datetime theo chuẩn VN
     * '2025-10-03 19:00:00' => 03/10/2025 19:00:SH
    */
    public static function formatVN_SC($datetime, $format = 'd/m/Y H:i:s', $timezone = 'Asia/Ho_Chi_Minh')
    {
        $datetimeVN = null;

        try {
            if (empty($datetime)) {
                // Nếu không truyền vào datetime thì lấy thời gian hiện tại
                $dt = new \DateTime('now', new \DateTimeZone($timezone));
            } else {
                // Nếu chuỗi là kiểu chuẩn SQL thì parse theo Y-m-d H:i:s
                $dt = \DateTime::createFromFormat('Y-m-d H:i:s', $datetime, new \DateTimeZone($timezone));

                // Nếu không phải kiểu chuẩn SQL thì thử parse theo format VN
                if ($dt === false) {
                    $dt = \DateTime::createFromFormat('m/d/Y\TH:i', $datetime, new \DateTimeZone($timezone));
                }
            }

            if ($dt !== false) {
                $datetimeVN = $dt->format($format); // Xuất ra theo format yêu cầu (mặc định d/m/Y H:i:s)
            }
        } catch (\Exception $e) {
            $datetimeVN = null;
        }

        return $datetimeVN;
    }

   public static function formatVN($datetime, $format = 'd/m/Y H:i:s', $timezone = 'Asia/Ho_Chi_Minh')
    {
        $datetimeVN = null;

        try {
            if (empty($datetime)) {
                // Nếu không truyền vào datetime thì lấy thời gian hiện tại
                $dt = new \DateTime('now', new \DateTimeZone($timezone));
            } else {
                // Nếu chuỗi là kiểu chuẩn SQL thì parse theo Y-m-d H:i:s
                $dt = \DateTime::createFromFormat('Y-m-d H:i:s', $datetime, new \DateTimeZone($timezone));

                // Nếu không phải kiểu chuẩn SQL thì thử parse theo format VN
                if ($dt === false) {
                    $dt = \DateTime::createFromFormat('d/m/Y H:i:s', $datetime, new \DateTimeZone($timezone));
                }
            }

            if ($dt !== false) {
                $datetimeVN = $dt->format($format); // Xuất ra theo format yêu cầu (mặc định d/m/Y H:i:s)
            }
        } catch (\Exception $e) {
            $datetimeVN = null;
        }

        return $datetimeVN;
    }


    /**
     * Convert string -> MySQL datetime (Y-m-d H:i:s)
     */
    public static function toMySQL($datetime, $fromFormat = 'd/m/Y H:i:s', $timezone = 'Asia/Ho_Chi_Minh')
    {
        if (empty($datetime)) {
            return null;
        }

        // 🔹 Chuẩn hóa chuỗi datetime trước khi parse
        // Trường hợp giây chỉ có 1 chữ số thì thêm số 0 vào cuối
        if (preg_match('/^\d{2}\/\d{2}\/\d{4} \d{2}:\d{2}:\d$/', $datetime)) {
            $datetime .= '0'; // ví dụ: 13:51:0 -> 13:51:00
        }

        $dt = \DateTime::createFromFormat($fromFormat, $datetime, new \DateTimeZone($timezone));

        if ($dt === false) {
            return null;
        }

        return $dt->format('Y-m-d H:i:s'); // chuẩn MySQL
    }

    /**
     * Lấy ngày hiện tại theo định dạng
     */
    public static function today($format = 'Y-m-d')
    {
        return date($format);
    }

    /**
     * Chuyển ngày từ định dạng dd/mm/yyyy sang yyyy-mm-dd (chuẩn MySQL)
     *
     * @param string $dateVN Ngày định dạng VN, ví dụ: '03/10/2025'
     * @return string|null Ngày chuẩn MySQL, ví dụ: '2025-10-03', hoặc null nếu không hợp lệ
     */
    public static function formatDateVNToMySQL(?string $dateVN): string
    {
        $dateVN = trim((string)$dateVN);

        // Nếu null hoặc rỗng → trả về ngày hôm nay (Y-m-d)
        if ($dateVN === '') {
            return date('Y-m-d');
        }

        $date = \DateTime::createFromFormat('d/m/Y', $dateVN);

        // Nếu hợp lệ → chuyển đổi
        if ($date && $date->format('d/m/Y') === $dateVN) {
            return $date->format('Y-m-d');
        }

        // Nếu không hợp lệ → cũng trả về ngày hôm nay
        return date('Y-m-d');
    }

    /**
     * Chuyển ngày từ định dạng yyyy-mm-dd (MySQL) sang dd/mm/yyyy (VN)
     *
     * @param string|null $dateMySQL Ngày chuẩn MySQL, ví dụ: '2025-10-03'
     * @return string|null Ngày định dạng VN, ví dụ: '03/10/2025', hoặc null nếu không hợp lệ
     */    
    public static function formatDateMySQLToVN(?string $dateMySQL): ?string
    {
        $dateMySQL = trim((string)$dateMySQL);

        // Nếu null hoặc rỗng → trả về ngày hiện tại (VN format)
        if ($dateMySQL === '') {
            return date('d/m/Y');
        }

        $date = \DateTime::createFromFormat('Y-m-d', $dateMySQL);

        // Nếu hợp lệ → chuyển đổi
        if ($date && $date->format('Y-m-d') === $dateMySQL) {
            return $date->format('d/m/Y');
        }

        // Nếu không hợp lệ → cũng trả về ngày hiện tại
        return date('d/m/Y');
    }



}
