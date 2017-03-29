<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use app\modules\admin\logic\DictionaryLogic;
use app\modules\admin\logic\SubscribeLogic;
use app\modules\admin\models\SubscribeMail;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class SubscribeController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message = 'Subscribe world')
    {
        echo $message . "\n";
    }

    public function actionSendMail() {
        $query = SubscribeMail::find()->where(array('status' => DictionaryLogic::indexKeyValue('MailStatus', 'Sending'), 'locked' => false))->orderBy('time ASC');
        $update = array('$set' => array('locked' => true));
        $mail = $query->modify($update, array('new' => 1));
        //$mail = SubscribeMail::find()->where(array('status' => DictionaryLogic::indexKeyValue('MailStatus', 'Sending')))->orderBy('time ASC')->one();
        if ($mail == null) {
            return;
        }
        SubscribeLogic::sendMail($mail);
    }
}
