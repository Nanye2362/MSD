<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cde_usedname".
 *
 * @property string $id
 * @property string $cde_name
 * @property string $cde_usedname
 * @property string $cde_usedname2
 * @property string $cde_usedname3
 */
class CdeUsedname extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'cde_usedname';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['cde_name', 'cde_usedname', 'cde_usedname2', 'cde_usedname3'], 'required'],
                [['cde_name', 'cde_usedname', 'cde_usedname2', 'cde_usedname3'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'cde_name' => 'Cde Name',
            'cde_usedname' => 'Cde Usedname',
            'cde_usedname2' => 'Cde Usedname2',
            'cde_usedname3' => 'Cde Usedname3',
        ];
    }

    static function getList($curPage, $pageSize) {
        $start = ($curPage - 1) * $pageSize;

        $cdeObj = CdeUsedname::find();

        $num = $cdeObj->count();

        $cde = $cdeObj->orderBy('`id` desc')->limit($pageSize)->offset($start)->asArray()->all();

        $obj = new \stdClass();
        $obj->totalRows = $num;
        $obj->curPage = $curPage;
        $obj->data = $cde;

        return $obj;
    }

}
