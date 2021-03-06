<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "china_drug_trials".
 *
 * @property string $id
 * @property string $code
 * @property string $first_date
 * @property string $status
 * @property string $drug_name
 * @property string $indications
 * @property string $popular_topic
 * @property string $sha224
 */
class ChinaDrugTrials extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'china_drug_trials';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['code', 'status', 'drug_name', 'indications', 'popular_topic', 'sha224'], 'required'],
                [['first_date'], 'safe'],
                [['code', 'sha224'], 'string', 'max' => 100],
                [['status', 'drug_name', 'indications', 'popular_topic'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'first_date' => 'First Date',
            'status' => 'Status',
            'drug_name' => 'Drug Name',
            'indications' => 'Indications',
            'popular_topic' => 'Popular Topic',
            'sha224' => 'Sha224',
        ];
    }

    /**
     * 返回临床数据
     * @param $cdeId
     * @return array|\yii\db\ActiveRecord[]
     */
    static function getChinaDrugByCdeId($curPage = 1, $pageSize = 20, $cdeId) {
        $start = ($curPage - 1) * $pageSize;
        $list = ChinaDrugTrials::find();
        $res = $list->select('"china_drug_trials"."id", "china_drug_trials"."code", to_char("first_date",\'yyyy-mm-dd\') "first_date", "china_drug_trials"."status", "china_drug_trials"."drug_name", "china_drug_trials"."indications", "china_drug_trials"."popular_topic", "china_drug_trials"."sponsor", "cde"."company"')
                ->rightJoin('cde_china_drug_trials', '"cde_china_drug_trials"."china_drug_trials_id"="china_drug_trials"."id"')
                ->innerJoin('cde', '"cde_china_drug_trials"."cde_id"="cde"."id"')
                ->where('"cde_china_drug_trials"."cde_id"=:id', [':id' => $cdeId])->orderBy('"cde_china_drug_trials"."uname", "cde_china_drug_trials"."inquire_type", "china_drug_trials"."drug_name"')->asArray()->all();
		
        $num = $list->count();
        
        $result = $list->limit($pageSize)->offset($start)->asArray()->all();
        
        $obj = new \stdClass();
        $obj->totalRows = $num;
        $obj->curPage = $curPage;
        $obj->data = $result;
        
        return $obj;
    }

}
