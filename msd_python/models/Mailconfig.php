<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "configuration".
 *
 * @property integer $id
 * @property string $name
 * @property string $value
 * @property string $note
 */
class Mailconfig extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'mail_config';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['name'], 'string', 'max' => 45],
                [['value'], 'string', 'max' => 45],
                [['note'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'value' => 'Value',
            'note' => '备注',
        ];
    }

    static function getList($curPage, $pageSize) {
        $start = ($curPage - 1) * $pageSize;

        $cdeObj = Mailconfig::find();

        $num = $cdeObj->count();

        $cde = $cdeObj->orderBy('"id" desc')->limit($pageSize)->offset($start)->asArray()->all();

        $obj = new \stdClass();
        $obj->totalRows = $num;
        $obj->curPage = $curPage;
        $obj->data = $cde;

        return $obj;
    }

}
