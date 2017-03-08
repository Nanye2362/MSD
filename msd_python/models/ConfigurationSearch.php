<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Configuration;

/**
 * ConfigurationSearch represents the model behind the search form about `app\models\Configuration`.
 */
class ConfigurationSearch extends Configuration {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['id'], 'integer'],
                [['name', 'value', 'note'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
//    static public function search($params) {
//        
//        $query = Configuration::find();
//        
//        // add conditions that should always apply here
//
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//        ]);
//        
//        $this->load($params);
//
//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to return any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }
//
//        // grid filtering conditions
//        $query->andFilterWhere([
//            'id' => $this->id,
//        ]);
//
//        $query->andFilterWhere(['like', 'name', $this->name])
//                ->andFilterWhere(['like', 'value', $this->value])
//                ->andFilterWhere(['like', 'note', $this->note]);
//                
//        return $dataProvider;
//    }

    static function search($curPage, $pageSize, $searchText) {
        $start = ($curPage - 1) * $pageSize;

        $configObj = Configuration::find();
        
        if (!empty($searchText)) {
            $configObj->andWhere("name like :searchText or value like :searchText or note like :searchText", [':searchText' => '%' . $searchText . '%']);
        }

        $num = $configObj->count();
        
        $configValues = $configObj->orderBy('`id` desc')->limit($pageSize)->offset($start)->asArray()->all();        

        $obj = new \stdClass();
        $obj->totalRows = $num;
        $obj->curPage = $curPage;
        $obj->data = $configValues;

        return $obj;
    }

}
