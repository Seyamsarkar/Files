<?php

use App\Constants\Status;
use App\Lib\Captcha;
use App\Lib\ClientInfo;
use App\Lib\CurlRequest;
use App\Lib\FileManager;
use App\Lib\GoogleAuthenticator;
use App\Models\CommissionLog;
use App\Models\Extension;
use App\Models\Frontend;
use App\Models\GeneralSetting;
use App\Models\Level;
use App\Models\Referral;
use App\Models\Transaction;
use App\Models\User;
use App\Notify\Notify;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

function systemDetails()
{
    $system['name']          = 'viserplace';
    $system['version']       = '2.0';
    $system['build_version'] = '4.3.9';
    return $system;
}

function slug($string)
{
    return Illuminate\Support\Str::slug($string);
}

function verificationCode($length)
{

    if ($length == 0) {
        return 0;
    }

    $min = pow(10, $length - 1);
    $max = (int) ($min - 1) . '9';
    return random_int($min, $max);
}

function getNumber($length = 8)
{
    $characters       = '1234567890';
    $charactersLength = strlen($characters);
    $randomString     = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomString;
}

function activeTemplate($asset = false)
{
    $general  = gs();
    $template = $general->active_template;

    if ($asset) {
        return 'assets/templates/' . $template . '/';
    }

    return 'templates.' . $template . '.';
}

function activeTemplateName()
{
    $general  = gs();
    $template = $general->active_template;
    return $template;
}

function loadReCaptcha()
{
    return Captcha::reCaptcha();
}

function loadCustomCaptcha($width = '100%', $height = 46, $bgColor = '#003')
{
    return Captcha::customCaptcha($width, $height, $bgColor);
}

function verifyCaptcha()
{
    return Captcha::verify();
}

function loadExtension($key)
{
    $analytics = Extension::where('act', $key)->where('status', Status::ENABLE)->first();
    return $analytics ? $analytics->generateScript() : '';
}

function getTrx($length = 12)
{
    $characters       = 'ABCDEFGHJKMNOPQRSTUVWXYZ123456789';
    $charactersLength = strlen($characters);
    $randomString     = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomString;
}

function getAmount($amount, $length = 2)
{
    $amount = round($amount ?? 0, $length);
    return $amount + 0;
}

function showAmount($amount, $decimal = 2, $separate = true, $exceptZeros = false)
{
    $separator = '';

    if ($separate) {
        $separator = ',';
    }

    $printAmount = number_format($amount, $decimal, '.', $separator);

    if ($exceptZeros) {
        $exp = explode('.', $printAmount);

        if ($exp[1] * 1 == 0) {
            $printAmount = $exp[0];
        } else {
            $printAmount = rtrim($printAmount, '0');
        }
    }

    return $printAmount;
}

function removeElement($array, $value)
{
    return array_diff($array, (is_array($value) ? $value : [$value]));
}

function cryptoQR($wallet)
{
    return "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$wallet&choe=UTF-8";
}

function keyToTitle($text)
{
    return ucfirst(preg_replace("/[^A-Za-z0-9 ]/", ' ', $text));
}

function titleToKey($text)
{
    return strtolower(str_replace(' ', '_', $text));
}

function strLimit($title = null, $length = 10)
{
    return Str::limit($title, $length);
}

function getIpInfo()
{
    $ipInfo = ClientInfo::ipInfo();
    return $ipInfo;
}

function osBrowser()
{
    $osBrowser = ClientInfo::osBrowser();
    return $osBrowser;
}

function getTemplates()
{
    $param['purchasecode'] = env("PURCHASECODE");
    $param['website']      = @$_SERVER['HTTP_HOST'] . @$_SERVER['REQUEST_URI'] . ' - ' . env("APP_URL");
    $url                   = 'https://license.viserlab.com/updates/templates/' . systemDetails()['name'];
    $response              = CurlRequest::curlPostContent($url, $param);

    if ($response) {
        return $response;
    } else {
        return null;
    }
}

function getPageSections($arr = false)
{
    $jsonUrl  = resource_path('views/') . str_replace('.', '/', activeTemplate()) . 'sections.json';
    $sections = json_decode(file_get_contents($jsonUrl));

    if ($arr) {
        $sections = json_decode(file_get_contents($jsonUrl), true);
        ksort($sections);
    }

    return $sections;
}

function getImage($image, $size = null)
{
    $clean = '';

    if (file_exists($image) && is_file($image)) {
        return asset($image) . $clean;
    }

    if ($size) {
        return route('placeholder.image', $size);
    }

    return asset('assets/images/default.png');
}

function notify($user, $templateName, $shortCodes = null, $sendVia = null, $createLog = true)
{
    $general          = gs();
    $globalShortCodes = [
        'site_name'       => $general->site_name,
        'site_currency'   => $general->cur_text,
        'currency_symbol' => $general->cur_sym,
    ];

    if (gettype($user) == 'array') {
        $user = (object) $user;
    }

    $shortCodes = array_merge($shortCodes ?? [], $globalShortCodes);

    $notify               = new Notify($sendVia);
    $notify->templateName = $templateName;
    $notify->shortCodes   = $shortCodes;
    $notify->user         = $user;
    $notify->createLog    = $createLog;
    $notify->userColumn   = isset($user->id) ? $user->getForeignKey() : 'user_id';
    $notify->send();
}

function getPaginate($paginate = 20)
{
    return $paginate;
}

function paginateLinks($data)
{
    return $data->appends(request()->all())->links();
}

function menuActive($routeName, $type = null, $param = null)
{

    if ($type == 3) {
        $class = 'side-menu--open';
    } elseif ($type == 2) {
        $class = 'sidebar-submenu__open';
    } else {
        $class = 'active';
    }

    if (is_array($routeName)) {

        foreach ($routeName as $key => $value) {

            if (request()->routeIs($value)) {
                return $class;
            }
        }
    } elseif (request()->routeIs($routeName)) {

        if ($param) {
            $routeParam = array_values(@request()->route()->parameters ?? []);
            if (strtolower(@$routeParam[0]) == strtolower($param)) {
                return $class;
            } else {
                return;
            }
        }
        return $class;
    }
}

function fileUploader($file, $location, $size = null, $old = null, $thumb = null)
{
    $fileManager        = new FileManager($file);
    $fileManager->path  = $location;
    $fileManager->size  = $size;
    $fileManager->old   = $old;
    $fileManager->thumb = $thumb;
    $fileManager->upload();
    return $fileManager->filename;
}

function removeFile($old = null)
{
    $fileManager = new FileManager();
    $fileManager->removeFile($old);
    return true;
}

function fileManager()
{
    return new FileManager();
}

function getFilePath($key)
{
    return fileManager()->$key()->path;
}

function getFileSize($key)
{
    return fileManager()->$key()->size;
}

function getFileThumb($key)
{
    return fileManager()->$key()->thumb;
}

function getFileExt($key)
{
    return fileManager()->$key()->extensions;
}

function diffForHumans($date)
{
    $lang = session()->get('lang');
    Carbon::setlocale($lang);
    return Carbon::parse($date)->diffForHumans();
}

function showDateTime($date, $format = 'Y-m-d h:i A')
{
    $lang = session()->get('lang');
    Carbon::setlocale($lang);
    return Carbon::parse($date)->translatedFormat($format);
}

function getContent($dataKeys, $singleQuery = false, $limit = null, $orderById = false)
{

    if ($singleQuery) {
        $content = Frontend::where('data_keys', $dataKeys)->orderBy('id', 'desc')->first();
    } else {
        $article = Frontend::query();
        $article->when($limit != null, function ($q) use ($limit) {
            return $q->limit($limit);
        });

        if ($orderById) {
            $content = $article->where('data_keys', $dataKeys)->orderBy('id')->get();
        } else {
            $content = $article->where('data_keys', $dataKeys)->orderBy('id', 'desc')->get();
        }
    }

    return $content;
}

function gatewayRedirectUrl($type = false)
{

    if ($type) {
        return 'user.deposit.history';
    } else {
        return 'user.deposit.index';
    }
}

function verifyG2fa($user, $code, $secret = null)
{
    $authenticator = new GoogleAuthenticator();

    if (!$secret) {
        $secret = $user->tsc;
    }

    $oneCode  = $authenticator->getCode($secret);
    $userCode = $code;

    if ($oneCode == $userCode) {
        $user->tv = 1;
        $user->save();
        return true;
    } else {
        return false;
    }
}

function urlPath($routeName, $routeParam = null)
{

    if ($routeParam == null) {
        $url = route($routeName);
    } else {
        $url = route($routeName, $routeParam);
    }

    $basePath = route('home');
    $path     = str_replace($basePath, '', $url);
    return $path;
}

function showMobileNumber($number)
{
    $length = strlen($number);
    return substr_replace($number, '***', 2, $length - 4);
}

function showEmailAddress($email)
{
    $endPosition = strpos($email, '@') - 1;
    return substr_replace($email, '***', 1, $endPosition);
}

function getRealIP()
{
    $ip = $_SERVER["REMOTE_ADDR"];

    //Deep detect ip
    if (filter_var(@$_SERVER['HTTP_FORWARDED'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_FORWARDED'];
    }

    if (filter_var(@$_SERVER['HTTP_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_FORWARDED_FOR'];
    }

    if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }

    if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }

    if (filter_var(@$_SERVER['HTTP_X_REAL_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_X_REAL_IP'];
    }

    if (filter_var(@$_SERVER['HTTP_CF_CONNECTING_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    }

    if ($ip == '::1') {
        $ip = '127.0.0.1';
    }

    return $ip;
}

function appendQuery($key, $value)
{
    return request()->fullUrlWithQuery([$key => $value]);
}

function dateSort($a, $b)
{
    return strtotime($a) - strtotime($b);
}

function dateSorting($arr)
{
    usort($arr, "dateSort");
    return $arr;
}

function gs()
{
    $general = Cache::get('GeneralSetting');
    if (!$general) {
        $general = GeneralSetting::first();
        Cache::put('GeneralSetting', $general);
    }

    return $general;
}

function authReviewer()
{
    return auth()->guard('reviewer')->user();
}

function authReviewerId()
{
    return auth()->guard('reviewer')->id();
}

function inputTitle($text)
{
    return ucfirst(preg_replace("/[^A-Za-z0-9 ]/", ' ', $text));
}

function levelCommission($id, $amount)
{
    $usr   = $id;
    $user  = User::find($id);
    $i     = 1;
    $gnl   = gs();
    $level = Referral::count();

    while ($usr != "" || $usr != "0" || $i < $level) {
        $me    = User::find($usr);
        $refer = User::find($me->ref_by);

        if ($refer == "") {
            break;
        }

        $commission = Referral::where('level', $i)->first();

        if (!$commission) {
            break;
        }

        $com = ($amount * $commission->percent) / 100;

        $referWallet          = $refer;
        $new_bal              = getAmount($referWallet->balance + $com);
        $referWallet->balance = $new_bal;
        $referWallet->save();
        $trx = getTrx();

        $transaction               = new Transaction();
        $transaction->user_id      = $refer->id;
        $transaction->amount       = getAmount($com);
        $transaction->post_balance = $new_bal;
        $transaction->charge       = 0;
        $transaction->trx_type     = '+';
        $transaction->details      = 'Level ' . $i . ' Referral Commission From ' . $user->username;
        $transaction->trx          = $trx;
        $transaction->remark       = 'referral_commission';
        $transaction->save();

        $commissionLog                    = new CommissionLog();
        $commissionLog->to_id             = $refer->id;
        $commissionLog->from_id           = $id;
        $commissionLog->level             = $i;
        $commissionLog->commission_amount = getAmount($com);
        $commissionLog->main_amo          = $new_bal;
        $commissionLog->trx_amo           = $amount;
        $commissionLog->title             = 'Level ' . $i . ' Referral Commission From ' . $user->username;
        $commissionLog->type              = 'deposit_commission';
        $commissionLog->percent           = $commission->percent;
        $commissionLog->trx               = $trx;
        $commissionLog->save();

        notify($refer, 'REFERRAL_COMMISSION', [
            'amount'       => getAmount($com),
            'main_balance' => $new_bal,
            'trx'          => $trx,
            'level'        => $i . ' level Referral Commission',
            'currency'     => $gnl->cur_text,
        ]);

        $usr = $refer->id;
        $i++;
    }

    return 0;
}

function uploadRemoteFile($file, $location, $fileExtension)
{
    $general = gs();
    $disk    = $general->server_name;
    diskConfigure();
    $disk = Storage::disk($disk);
    makeRemoteDirectory($location, $disk);
    $path = uniqid() . time() . '.' . $fileExtension;
    $disk->put($location . '/' . $path, $file);
    return ['success', $location . '/' . $path];
}

function diskConfigure()
{
    $general = gs();
    //ftp
    Config::set('filesystems.disks.custom-ftp.driver', 'ftp');
    Config::set('filesystems.disks.custom-ftp.host', $general->ftp->host);
    Config::set('filesystems.disks.custom-ftp.username', $general->ftp->username);
    Config::set('filesystems.disks.custom-ftp.password', $general->ftp->password);
    Config::set('filesystems.disks.custom-ftp.port', 21);
    Config::set('filesystems.disks.custom-ftp.root', $general->ftp->root);
}

function makeRemoteDirectory($path, $disk)
{

    if ($disk->exists($path)) {
        return true;
    }

    $disk->makeDirectory($path);
}

function removeRemoteFile($video, $disk)
{
    $gnl = gs();
    diskConfigure();
    $path    = 'assets/videos/' . $video;
    $storage = Storage::disk($disk);

    if (file_exists($path) && is_file($path)) {
        @unlink($path);
        return true;
    } elseif ($storage->exists($video)) {
        $storage->delete($video);
    } else {
        return false;
    }
}

function displayRating($rating)
{
    $ratings = '';
    if ($rating > 0) {
        $avgRating  = $rating;
        $integerVal = floor($avgRating);
        $fraction   = $avgRating - $integerVal;

        if ($fraction <= .25) {
            $avgRating = intval($avgRating);
        }
        if ($fraction >= .75) {
            $avgRating = intval($avgRating) + 1;
        }
        for ($i = 1; $i <= $avgRating; $i++) {
            $ratings .= '<i class="las la-star"></i>';
        }
        if ($fraction > .25 && $fraction < .75) {
            $avgRating += 1;
            $ratings .= '<i class="las la-star-half-alt"></i>';
        }
    } else {
        $avgRating = 0;
    }
    $nonStar = 5 - intval($avgRating);
    for ($k = 1; $k <= $nonStar; $k++) {
        $ratings .= '<i class="lar la-star"></i>';
    }
    return $ratings;
}

function updateAuthorLevel($author)
{
    $authNextLevel = Level::where('earning', '>=', $author->earning)->orderBy('earning', 'asc')->first();
    if ($authNextLevel) {
        $author->level_id = $authNextLevel->id;
        $author->save();
        notify($author, 'LEVEL_UPGRADE', [
            'name' => $authNextLevel->name,
        ]);
    }
}

function weeklyDates()
{
    $startWeek = Carbon::now()->subWeek()->startOfWeek();
    $endWeek   = Carbon::now()->subWeek()->endOfWeek();
    $dates     = [$startWeek, $endWeek];
    return $dates;
}
