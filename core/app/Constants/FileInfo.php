<?php

namespace App\Constants;

class FileInfo
{

    /*
    |--------------------------------------------------------------------------
    | File Information
    |--------------------------------------------------------------------------
    |
    | This class basically contain the path of files and size of images.
    | All information are stored as an array. Developer will be able to access
    | this info as method and property using FileManager class.
    |
     */

    public function fileInfo()
    {
        $data['withdrawVerify'] = [
            'path' => 'assets/images/verify/withdraw',
        ];
        $data['depositVerify'] = [
            'path' => 'assets/images/verify/deposit',
        ];
        $data['verify'] = [
            'path' => 'assets/verify',
        ];
        $data['default'] = [
            'path' => 'assets/images/default.png',
        ];
        $data['withdrawMethod'] = [
            'path' => 'assets/images/withdraw/method',
            'size' => '800x800',
        ];
        $data['ticket'] = [
            'path' => 'assets/support',
        ];
        $data['logoIcon'] = [
            'path' => 'assets/images/logoIcon',
        ];
        $data['favicon'] = [
            'size' => '128x128',
        ];
        $data['extensions'] = [
            'path' => 'assets/images/extensions',
            'size' => '36x36',
        ];
        $data['seo'] = [
            'path' => 'assets/images/seo',
            'size' => '1180x600',
        ];
        $data['userProfile'] = [
            'path' => 'assets/images/user/profile',
            'size' => '90x90',
        ];
        $data['userCoverImage'] = [
            'path' => 'assets/images/user/profile/cover',
            'size' => '835x345',
        ];
        $data['adminProfile'] = [
            'path' => 'assets/admin/images/profile',
            'size' => '400x400',
        ];
        $data['category'] = [
            'path' => 'assets/images/category',
            'size' => '60x60',
        ];
        $data['level'] = [
            'path' => 'assets/images/level',
            'size' => '35x35',
        ];
        $data['reviewer'] = [
            'path' => 'assets/images/reviewer',
            'size' => '400x400',
        ];
        $data['product'] = [
            'path'  => 'assets/images/product',
            'size'  => '1180x600',
            'thumb' => '590x300',
        ];
        $data['productFile'] = [
            'path' => 'assets/product',
        ];
        $data['tempProduct'] = [
            'path'  => 'assets/images/tempProduct',
            'size'  => '1180x600',
            'thumb' => '590x300',
        ];
        $data['tempProductFile'] = [
            'path' => 'assets/tempProduct',
        ];
        return $data;
    }
}
