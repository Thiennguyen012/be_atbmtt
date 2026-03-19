<?php

namespace App\CPU;

use App\Model\RoomBooking;
use App\Models\RoomBooking\RoomBookingDetail;
use App\Models\SiteInfo;
use App\Repositories\User\UserRepository;
use App\Repositories\Hotel\RoomRepository;
use App\Repositories\RoomBooking\RoomBookingRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Random\RandomException;

class Helpers
{
    const LIMIT_PER_PAGE = 20;
    const TYPE_IMAGE = 1;
    const TYPE_IMAGE_IPHONE = 2;
    const TYPE_VIDEO = 3;

    public static function remove_invalid_charcaters($str): string
    {
        return str_ireplace(['\'', '"', ',', ';', '<', '>', '?'], ' ', preg_replace('/\s\s+/', ' ', $str));
    }

    public static function permission_check($permission_name): bool
    {
        $admin = Auth::guard('user')->user();
        if ($admin->is_super_admin == UserRepository::IS_SUPER_ADMIN || $admin->can($permission_name)) {
            return true;
        }
        return false;
    }
    static function getExtension($ex)
    {
        $ex_img = ['png', 'jpg', 'jpeg'];
        $ex_iphone_img = ['heif', 'heic'];
        $ex_video = ['mp4', 'mov'];
        if (in_array($ex, $ex_img)) {
            return self::TYPE_IMAGE;
        }
        if (in_array($ex, $ex_iphone_img)) {
            return self::TYPE_IMAGE_IPHONE;
        }
        if (in_array($ex, $ex_video)) {
            return self::TYPE_VIDEO;
        }
        return false;
    }


    public static function image_path($image_path): string
    {
        return $image_path ? asset('/storage' . $image_path) : '';
    }

    /**
     * @throws RandomException
     */
    public static function generateUniqueString($length = 16): string
    {
        return bin2hex(random_bytes($length));
    }

    // public static function allLanguage(): array
    // {
    //     return [
    //         [
    //             'name' => 'Tiếng việt',
    //             'code' => 'vi',
    //             'icon' => '/assets/front-end/img/flags/vi.png',
    //         ],
    //         [
    //             'name' => 'English',
    //             'code' => 'en',
    //             'icon' => '/assets/front-end/img/flags/en.png',
    //         ],
    //         [
    //             'name' => 'Japan',
    //             'code' => 'jp',
    //             'icon' => '/assets/front-end/img/flags/jp.png',
    //         ],
    //         [
    //             'name' => 'China',
    //             'code' => 'cn',
    //             'icon' => '/assets/front-end/img/flags/cn.png',
    //         ],
    //         [
    //             'name' => 'Korea',
    //             'code' => 'kor',
    //             'icon' => '/assets/front-end/img/flags/kor.png',
    //         ]
    //     ];
    // }

    // public static function languageValueDefault(): array
    // {
    //     return [
    //         [
    //             'name' => 'Tiếng việt',
    //             'code' => 'vi',
    //             'icon' => '/assets/front-end/img/flags/vi.png',
    //             'status' => 1,
    //             'default' => true
    //         ]
    //     ];
    // }

    // public static function languageDefault($lang = []): string
    // {
    //     foreach ($lang as $item) {
    //         if ($item['default']) {
    //             return $item['code'];
    //         }
    //     }
    //     return '';
    // }

    // public static function error_processor($validator): array
    // {
    //     $err_keeper = [];
    //     foreach ($validator->errors()->getMessages() as $index => $error) {
    //         $err_keeper[] = ['code' => $index, 'message' => $error[0]];
    //     }
    //     return $err_keeper;
    // }

    public static function cleanValue($value): string
    {
        if (is_null($value)) return '';
        return trim((string)$value);
    }

}

// if (!function_exists('translate')) {
//     function translate($key): string
//     {
//         return __('messages.' . $key);
//     }
// }



