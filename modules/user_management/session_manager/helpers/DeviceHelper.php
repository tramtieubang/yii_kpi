<?php
namespace app\modules\user_management\session_manager\helpers;

class DeviceHelper
{
    /**
     * Nhận diện thiết bị và trình duyệt từ User-Agent.
     * Trả về chuỗi ví dụ: "Windows / Chrome", "Android / Firefox", "iPhone / Safari"...
     */
    public static function detect($userAgent)
    {
        $os = self::getOS($userAgent);
        $browser = self::getBrowser($userAgent);

        return trim($os . ' / ' . $browser, ' /');
    }

    private static function getOS($userAgent)
    {
        $osArray = [
            'Windows' => '/Windows/i',
            'Mac OS' => '/Macintosh|Mac OS X/i',
            'Linux' => '/Linux/i',
            'Android' => '/Android/i',
            'iOS' => '/iPhone|iPad|iPod/i',
        ];

        foreach ($osArray as $os => $regex) {
            if (preg_match($regex, $userAgent)) {
                return $os;
            }
        }
        return 'Unknown OS';
    }

    private static function getBrowser($userAgent)
    {
        $browserArray = [
            'Edge' => '/Edg/i',
            'Chrome' => '/Chrome/i',
            'Firefox' => '/Firefox/i',
            'Safari' => '/Safari/i',
            'Opera' => '/Opera|OPR/i',
            'IE' => '/MSIE|Trident/i',
        ];

        foreach ($browserArray as $browser => $regex) {
            if (preg_match($regex, $userAgent)) {
                return $browser;
            }
        }
        return 'Unknown Browser';
    }
}
