<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: Image.php
 * $Id: Image.php v 1.0 2015-11-03 20:59:50 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-03-22 22:59:30 $
 * @brief 图片处理类，做简单的图片thumb生成，变方形操作
 *
 ******************************************************************/

namespace app\components;

class Image extends BaseObject {

    public static function openImage($file) {
        if (!file_exists($file)) {
            return null;
        }
        // 图像类型
        $type = strtolower(substr($file, strrpos($file, '.')+1));
        $supportType = array('jpg', 'jpeg', 'png', 'gif');
        if (!in_array($type, $supportType, true)) {
            return null;
        }
        $img = null;
        switch ($type) {
        case 'jpg':
        case 'jpeg':
            $img = imagecreatefromjpeg($file);
            break;
        case 'png':
            $img = imagecreatefrompng($file);
            break;
        case 'gif':
            $img = imagecreatefromgif($file);
            break;
        default:
            break;
        }
        return $img;
    }

    public static function saveImage($img, $dstFile) {
        $type = substr($dstFile, strrpos($dstFile, '.')+1);
        switch($type) {
        case 'jpg':
        case 'jpeg':
            imagejpeg($img, $dstFile, 100);
            break;
        case 'png':
            imagepng($img, $dstFile, 9);
            break;
        case 'gif':
            imagegif($img, $dstFile, 100);
            break;
        default:
            break;
        }
    }

    /**
     * 把一个图像裁剪为任意大小的图像，图像不变形
     * @param $srcFile 需要处理的图片文件
     * 参数说明：输入 需要处理图片的 文件名，生成新图片的保存文件名，生成新图片的宽，生成新图片的高
     */
    // 获得任意大小图像，不足地方拉伸，不产生变形，不留下空白
    public static function resize($srcFile, $dstFile , $newWidth , $newHeight) {
        $newWidth = intval($newWidth);
        $newHeight = intval($newHeight);
        if ($newWidth < 1 || $newHeight < 1) {
            return false;
        }
        $srcImg = Image::openImage($srcFile);
        if ($srcImg == null) {
            return false;
        }
        $w = imagesx($srcImg);
        $h = imagesy($srcImg);
        $ratioW = 1.0 * $newWidth / $w;
        $ratioH = 1.0 * $newHeight / $h;
        $ratio = 1.0;
        // 生成的图像的高宽比原来的都小，或都大 ，原则是 取大比例放大，取大比例缩小（缩小的比例就比较小了）
        if ( ($ratioW < 1 && $ratioH < 1) || ($ratioW > 1 && $ratioH > 1)) {
            if ($ratioW < $ratioH) {
                $ratio = $ratioH; // 情况一，宽度的比例比高度方向的小，按照高度的比例标准来裁剪或放大
            } else {
                $ratio = $ratioW;
            }
            // 定义一个中间的临时图像，该图像的宽高比 正好满足目标要求
            $interW = (int)($newWidth / $ratio);
            $interH = (int)($newHeight / $ratio);
            $interImg = imagecreatetruecolor($interW, $interH);
            imagecopy($interImg, $srcImg, 0, 0, abs(intval(($interW - $w) / 2)), abs(intval(($interH - $h) / 2)), $interW, $interH);
            // 生成一个以最大边长度为大小的是目标图像$ratio比例的临时图像
            // 定义一个新的图像
            $newImg = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($newImg, $interImg, 0, 0, 0, 0, $newWidth, $newHeight, $interW, $interH);
            Image::saveImage($newImg, $dstFile);
        }
        else{
            $ratio = $ratioH > $ratioW ? $ratioH : $ratioW; //取比例大的那个值
            // 定义一个中间的大图像，该图像的高或宽和目标图像相等，然后对原图放大
            $interW = (int)($w * $ratio);
            $interH = (int)($h * $ratio);
            $interImg = imagecreatetruecolor($interW, $interH);
            //将原图缩放比例后裁剪
            imagecopyresampled($interImg, $srcImg, 0, 0, 0, 0, $interW, $interH, $w, $h);
            // 定义一个新的图像
            $newImg = imagecreatetruecolor($newWidth, $newHeight);
            imagecopy($newImg, $interImg, 0, 0, 0, 0, $newWidth, $newHeight);
            Image::saveImage($newImg, $dstFile);
        }
        return true;
    }

    public static function square($srcFile, $dstFile) {
        $img = Image::openImage($srcFile);
        if ($img == null) {
            return false;
        }
        $w = imagesx($img);
        $h = imagesy($img);
        if ($w == $h) {
            Image::saveImage($img, $dstFile);
        }
        else {
            $newW = $w > $h ? $h : $w;
            $newH = $newW;
            Image::resize($srcFile, $dstFile, $newW, $newH);
        }
        return true;
    }
}
