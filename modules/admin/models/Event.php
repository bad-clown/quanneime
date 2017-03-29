<?php

namespace app\modules\admin\models;

use Yii;
use app\models\I18n;

/**
 * This is the model class for table "Event".
 *
 */
class Event extends \app\components\BaseMongoActiveRecord {
    /**
     * @inheritdoc
     */
    public static function collectionName() {
        return 'Event';
    }

    public static function primaryKey() {
        return ['_id'];
    }

    public function rules() {
        return [
            [["type"],"required"],
            [["nodeId","idx"],"safe"]
            ];
    }

    public function attributes() {
        return array_merge($this->primaryKey(), ['type', 'stationId', 'time', 'data', 'status']);
    }

    public function attributeLabels() {
        return [
            'type' => I18n::text('EventType'),
            'stationId' => I18n::text('StationId'),
            'time' => I18n::text('EventTime'),
            'status' => I18n::text('EventStatus'),
        ];
    }
}
