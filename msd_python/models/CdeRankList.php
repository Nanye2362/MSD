<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cde_rank_list".
 *
 * @property integer $id
 * @property string $cde_id
 * @property string $rank
 * @property string $datetime
 */
class CdeRankList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cde_rank_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cde_id', 'rank'], 'required'],
            [['cde_id', 'rank'], 'integer'],
            [['datetime'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cde_id' => 'Cde ID',
            'rank' => 'Rank',
            'datetime' => 'Datetime',
        ];
    }
}
