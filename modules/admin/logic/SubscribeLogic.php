<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: SubscribeLogic.php
 * $Id: SubscribeLogic.php v 1.0 2015-11-02 20:06:50 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-05-13 13:59:04 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\admin\logic;

use Yii;
use app\components\BaseObject;
use app\modules\admin\logic\DictionaryLogic;
use app\modules\admin\models\SubscribeMail;
use app\modules\admin\models\SendedMail;
use app\modules\admin\models\Subscriber;

class SubscribeLogic extends BaseObject {
    public static function sendMail($mail) {
        $all = SendedMail::find()->where(array('mailId' => $mail->_id))->all();
        $sended = array();
        foreach ($all as $row) {
            $sended[] = (string)$row->subscriberId;
        }
        $mail->status = DictionaryLogic::indexKeyValue('MailStatus', 'Sending');
        $mail->save();
        $all = Subscriber::find()->all();
        $allSend = true;
        foreach ($all as $row) {
            if (in_array((string)$row->_id, $sended)) {
                continue;
            }
            $content = self::buildContent($mail, $row->_id);
            if (self::sendMailTo($row->email, $mail->title, $content)) {
                $sm = new SendedMail();
                $sm->mailId = $mail->_id;
                $sm->subscriberId = $row->_id;
                $sm->time = time();
                $sm->save();
                sleep(1);
            }
            else {
                $allSend = false;
            }
        }
        $mail->status = DictionaryLogic::indexKeyValue('MailStatus', 'Complete');
        $mail->save();
    }

    public static function buildContent($mail, $subscriberId) {
        $url = DictionaryLogic::indexKeyValue('App', 'Host') . '/index.php?r=job/unsubscribe&id=' . (string)$subscriberId;
        $tail = '<br /> <br />圈内觅 | <a href="' . $url . '">退订邮件</a><br />';
        $content = $mail->content . $tail;
        return $content;
    }

    public static function sendMailTo($receiver, $title, $content) {
        try {
            $mail = \Yii::$app->mailer->compose();
            $mail->setTo($receiver);
            $mail->setFrom(\Yii::$app->params["mailFrom"]);
            $mail->setSubject($title);
            $mail->setHtmlBody($content);
            return $mail->send();
        }
        catch (Exception $e) {
            return false;
        }
    }
}
