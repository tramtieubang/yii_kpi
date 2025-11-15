<?php

namespace app\custom;


use webvimark\modules\UserManagement\models\User;

class CustomFunc
{
    /**
     * chuyen doi ngay chuoi Y-m-d H:i:s -> dd/mm/yyyy H:i:s
     * @param string $date_string
     * @return string
     */
    public static function convertYMDHISToDMYHIS($date_string)
    {
        return $date_string != null ? date("Y-m-d H:i:s", strtotime($date_string)) : '';
    }

    /**
     * chuyen doi ngay chuoi Y-m-d -> dd/mm/yyyy
     * @param string $date_string
     * @return string
     */
    public static function convertYMDToDMY($date_string)
    {
        if ($date_string != null) {
            $dateTime = \DateTime::createFromFormat('Y-m-d', $date_string);
            return $dateTime ? $dateTime->format('d/m/Y') : '';
        } else
            return '';
    }



    /**
     * chuyen doi ngay chuoi dd/mm/yyyy -> Y-m-d để lưu CSDL
     * @param string $date_string
     * @return string
     */
    public static function convertDMYToYMD($date_string)
    {
        if ($date_string != null) {
            $dateTime = \DateTime::createFromFormat('d/m/Y', $date_string);
            return $dateTime ? $dateTime->format('Y-m-d') : '';
        } else
            return '';
    }

    /**
     * generate random 4 string or number
     */
    function generateRandomString()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        // Shuffle the characters
        $shuffledCharacters = str_shuffle($characters);

        // Take the first 4 characters
        $randomString = substr($shuffledCharacters, 0, 4);

        return $randomString;
    }

    /**
     * get string limit by char
     */
    public static function getStringByChars($string, $numChar)
    {
        if ($string != '' && strlen($string) > $numChar) {
            $pos = strpos($string, ' ', $numChar);
            return substr($string, 0, $pos) . ' ..';
        } else {
            return $string;
        }
    }
    /**
     * get string limit by char in file name & extension
     */
    public static function getStringByCharsForFileName($string, $numChar)
    {
        $strUse = explode('.', $string);
        $str1 = $strUse[0];
        $str2 = $strUse[1];
        $strAfter = '';
        if ($str1 != null) {
            if (strlen($str1) > $numChar) {
                $pos = strpos($str1, ' ', $numChar);
                $strAfter = substr($str1, 0, $pos) . '(..)';
            } else {
                $strAfter = $str1;
            }
        }
        return $strAfter . '.' . $str2;
    }

    /**
     * doc so tien thanh chu
     */
    function chuyenSoTienThanhChu($number)
    {
        $hyphen      = ' ';
        $conjunction = '  ';
        $separator   = ' ';
        $negative    = 'âm ';
        $decimal     = ' phẩy ';
        $dictionary  = array(
            0                   => 'không',
            1                   => 'một',
            2                   => 'hai',
            3                   => 'ba',
            4                   => 'bốn',
            5                   => 'năm',
            6                   => 'sáu',
            7                   => 'bảy',
            8                   => 'tám',
            9                   => 'chín',
            10                  => 'mười',
            11                  => 'mười một',
            12                  => 'mười hai',
            13                  => 'mười ba',
            14                  => 'mười bốn',
            15                  => 'mười năm',
            16                  => 'mười sáu',
            17                  => 'mười bảy',
            18                  => 'mười tám',
            19                  => 'mười chín',
            20                  => 'hai mươi',
            30                  => 'ba mươi',
            40                  => 'bốn mươi',
            50                  => 'năm mươi',
            60                  => 'sáu mươi',
            70                  => 'bảy mươi',
            80                  => 'tám mươi',
            90                  => 'chín mươi',
            100                 => 'trăm',
            1000                => 'nghìn',
            1000000             => 'triệu',
            1000000000          => 'tỷ',
            1000000000000       => 'nghìn tỷ',
            1000000000000000    => 'nghìn triệu triệu',
            1000000000000000000 => 'tỷ tỷ'
        );
        if (!is_numeric($number)) {
            return false;
        }
        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }
        if ($number < 0) {
            return $negative . chuyenSoTienThanhChu(abs($number));
        }
        $string = $fraction = null;
        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }
        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . $this->chuyenSoTienThanhChu($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->chuyenSoTienThanhChu($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->chuyenSoTienThanhChu($remainder);
                }
                break;
        }
        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }
        return $string;
    }

    protected function convertDateFormat($date, $fromFormat, $toFormat)
    {
        $dateTime = \DateTime::createFromFormat($fromFormat, $date);
        return $dateTime ? $dateTime->format($toFormat) : null;
    }

    public function convertDMYToYMD2($date)
    {
        return $this->convertDateFormat($date, 'd/m/Y', 'Y-m-d');
    }

    public function convertYMDToDMY2($date)
    {
        return $this->convertDateFormat($date, 'Y-m-d', 'd/m/Y');
    }

    public static function formatPhoneNumber($phone, $format = '$1.$2.$3')
    {
        return preg_replace('/^(\d{4})(\d{3})(\d{3})$/', $format, $phone);
    }

    public static function toLocaleStringVN($number, $decimals = 2)
    {
        return number_format($number, $decimals, ',', '.');
    }

    public static function mRound($number, $multiple = 10000)
    {
        return round($number / $multiple) * $multiple;
    }
}
