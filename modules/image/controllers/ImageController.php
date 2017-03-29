<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: ImageController.php
 * $Id: ImageController.php v 1.0 2015-11-03 22:51:59 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-03-01 23:16:21 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\image\controllers;

use Yii;
use app\modules\image\logic\ImageLogic;
use app\modules\admin\logic\DictionaryLogic;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\BaseController;
use app\modules\admin\models\Dictionary;

/**
 * ImageController implements the CRUD actions for Image model.
 */
class ImageController extends BaseController {
    public $enableCsrfValidation = false;// 去掉csrf验证
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionUploadCompLogo() {
        $pic = UploadedFile::getInstanceByName('pic');
        $src = $pic->tempName . '.'.$pic->extension;
        rename($pic->tempName, $src);
        $dst = $pic->tempName . NOW . rand(100, 999) . '.' . $pic->extension;
        $dstFilename = substr($dst, strrpos($dst, '/')+1);
        $url = ImageLogic::saveCompanyLogo($src, $dstFilename);

        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = array('url' => $url);
    }

    public function actionUploadNormal() {
        $pic = UploadedFile::getInstanceByName('pic');
        $src = $pic->tempName . '.'.$pic->extension;
        rename($pic->tempName, $src);
        $dst = $pic->tempName . NOW . rand(100, 999) . '.' . $pic->extension;
        $dstFilename = substr($dst, strrpos($dst, '/')+1);
        ImageLogic::saveImage($src, $dstFilename);

        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = array('url' => '/data/' . $dstFilename);
    }

    public function actionUploadCard() {
        $file = UploadedFile::getInstanceByName('card');
        $ret = array('code' => DictionaryLogic::getErrorCode('Success'));
        if ($file == null) {
            $ret['code'] = DictionaryLogic::getErrorCode('UploadFailed');
        }
        else {
            $src = $file->tempName;
            $dst = $file->tempName . NOW . rand(100, 999) . '.' . $file->extension;
            $dst = substr($dst, strrpos($dst, '/')+1);
            $dstPath = WEBROOT . '/data/card/';
            if (is_dir($dstPath) == false) {
                mkdir($dstPath, 0777, true);
            }
            rename($file->tempName, $dstPath . $dst);
            $ret['url'] = '/data/card/' . $dst;
        }
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $ret;
    }
}
