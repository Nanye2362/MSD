<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Indicationstypes;

/**
 * ConfigurationSearch represents the model behind the search form about `app\models\Configuration`.
 */
class IndicationstypesSearch extends Indicationstypes {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['id'], 'integer'],
                [['english_name', 'chinese_name', 'ephmra_atc_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }    

    static function search($curPage, $pageSize, $uid, $searchText) {
        $start = ($curPage - 1) * $pageSize;

        $IndicationstypesObj = Indicationstypes::find();
        
        if (!empty($searchText)) {
            $IndicationstypesObj->andWhere("english_name like :searchText or chinese_name like :searchText or ephmra_atc_code like :searchText", [':searchText' => '%' . $searchText . '%']);
        }

        $num = $IndicationstypesObj->count();
        
        $IndicationstypesValues = $IndicationstypesObj->orderBy('`id` desc')->limit($pageSize)->offset($start)->asArray()->all();        

        $obj = new \stdClass();
        $obj->totalRows = $num;
        $obj->curPage = $curPage;
        $obj->data = $IndicationstypesValues;

        return $obj;
    }

}
