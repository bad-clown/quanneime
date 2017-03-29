<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: ImageLogic.php
 * $Id: ImageLogic.php v 1.0 2015-11-03 22:32:37 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-02-29 22:54:31 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\image\logic;

use Yii;
use app\components\BaseObject;
use app\components\Image;

class ImageLogic extends BaseObject {
    public static function saveCompanyLogo($src, $dstFilename) {
        $thumbPath = WEBROOT . '/data/thumb/';
        $thumbDst = $thumbPath . $dstFilename;
        if (is_dir($thumbPath) == false) {
            mkdir($thumbPath, 0777, true);
        }
        $newSrc = self::saveImage($src, $dstFilename);
        if ( $newSrc == '') {
            return '/data/miss.jpg';
        }
        $src = WEBROOT . $newSrc;
        if (Image::resize($src, $thumbDst, 262, 100) == false) {
            return '';
        }
        return '/data/thumb/' . $dstFilename;
    }

    public static function saveUserAvatar($src, $dstFilename) {
        $thumbPath = WEBROOT . '/data/avatar/';
        $thumbDst = $thumbPath . $dstFilename;
        if (is_dir($thumbPath) == false) {
            mkdir($thumbPath, 0777, true);
        }
        $newSrc = self::saveImage($src, $dstFilename);
        if ( $newSrc == '') {
            return '';
        }
        $src = WEBROOT . $newSrc;
        if (Image::resize($src, $thumbDst, 163, 163) == false) {
            return '';
        }
        return '/data/avatar/' . $dstFilename;
    }

    /**
     * 保存文件到data目录并生产thumb和square图片，返回thumb图片路径
     */
    public static function genThumbAndSquareImage($src, $dstFilename) {
        $thumbPath = WEBROOT . '/data/thumb/';
        $squarePath = WEBROOT . '/data/square/';
        $thumbDst = $thumbPath . $dstFilename;
        $squareDst = $squarePath . $dstFilename;
        if (is_dir($thumbPath) == false) {
            mkdir($thumbPath, 0777, true);
        }
        if (is_dir($squarePath) == false) {
            mkdir($squarePath, 0777, true);
        }
        $newSrc = self::saveImage($src, $dstFilename);
        if ( $newSrc == '') {
            return '';
        }
        $src = WEBROOT . $newSrc;
        if (Image::resize($src, $thumbDst, 220, 220) == false) {
            return '';
        }
        if (Image::square($src, $squareDst) == false) {
            return '';
        }
        return array(
            'thumb' => '/data/thumb/' . $dstFilename,
            'src' => '/data/square/' . $dstFilename,
        );
    }

    /**
     * 保存文件到data目录，并返回目标文件路径
     */
    public static function saveImage($src, $dstFilename) {
        $dst = WEBROOT . '/data/' . $dstFilename;
        if (file_exists($src) == false) {
            return '';
        }
        if (rename($src, $dst) == false) {
            return '';
        }
        return '/data/' . $dstFilename;
    }
}
