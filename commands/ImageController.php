<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: ImageController.php
 * $Id: ImageController.php v 1.0 2015-11-03 21:15:15 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2015-11-03 23:28:15 $
 * @brief
 *
 ******************************************************************/

namespace app\commands;

use yii\console\Controller;
use app\components\Image;
use app\modules\image\logic\ImageLogic;

/**
 * This command test image functions.
 *
 */
class ImageController extends Controller {

    /**
     * resize一个图片
     * @param @srcFile 原图片路径
     * @param @dstFile 目标图片路径
     * @param @newWidth 新图片的宽，默认为220
     * @param @newHeight 新图片的高, 默认为220
     */
    public function actionResize($srcFile, $dstFile, $newWidth = 220, $newHeight = 220) {
        var_dump(Image::resize($srcFile, $dstFile, $newWidth, $newHeight));
    }

    /**
     * resize一个图片，使这个图片变成正方形，宽和高相等
     * @param $srcFile 原图片文件绝对路径
     * @param $dstFile 目标文件绝对路径
     */
    public function actionSquare($srcFile, $dstFile) {
        var_dump(Image::square($srcFile, $dstFile));
    }

    /**
     * 将图片放入data文件夹，并且生产thumb和square图片文件
     * @param $srcFile 原图片文件绝对路径
     */
    public function actionGenThumbAndSquare($srcFile) {
        $base = substr($srcFile, 0, strrpos($srcFile, '.'));
        $ext = substr($srcFile, strrpos($srcFile, '.'));
        $dstFilename = $base . NOW . rand(100, 999) . $ext;
        $dstFilename = substr($dstFilename, strrpos($dstFilename, '/')+1);
        echo ImageLogic::genThumbAndSquareImage($srcFile, $dstFilename);
        echo "\n";
    }
}
