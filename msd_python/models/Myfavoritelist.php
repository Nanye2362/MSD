<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cde_favorite".
 *
 * @property string $id
 * @property string $user_id
 * @property string $cde_id
 */
class Myfavoritelist extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cde_favorite';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'cde_id'], 'integer'],
            [['user_id', 'cde_id'], 'unique', 'targetAttribute' => ['user_id', 'cde_id'], 'message' => 'The combination of User ID and Cde ID has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'cde_id' => 'Cde ID',
        ];
    }
    
    static function getList($curPage, $pageSize, $typeId, $searchText, $uid) {
        $start = ($curPage - 1) * $pageSize;

        $cdeObj = Cde::find()->leftJoin('indications_types', '"indications_types"."id"="cde"."indication_id"')->with('rankList')->with('publicremark');

        if (!empty($typeId)) {
            $cdeObj->andWhere('"tid"=:tid', [':tid' => $typeId]);
        }

        /*if (!empty($searchText)) {
            if (is_array($searchText)) {
                $cde_name = $searchText[1];
                $cdeObj->andWhere('"code" like :searchText or "name" like :searchText or "name" like :cde_name or "company" like :searchText', [':searchText' => '%' . $searchText[0] . '%', ':cde_name' => '%' . $cde_name . '%']);
            } else {
                $cdeObj->andWhere('"code" like :searchText or "name" like :searchText or "company" like :searchText', [':searchText' => '%' . $searchText . '%']);
            }
        }*/
		
		if (!empty($searchText)) {
            if (is_array($searchText)) {
                $cde_name = $searchText[0];
				$cde_used_name = !empty($searchText[1]) ? $searchText[1] : $cde_name;
				$cde_used_name2 = !empty($searchText[2]) ? $searchText[2] : $cde_name;
				$cde_used_name3 = !empty($searchText[3]) ? $searchText[3] : $cde_name;
				$cde_used_name4 = !empty($searchText[4]) ? $searchText[4] : $cde_name;
				$cde_used_name5 = !empty($searchText[5]) ? $searchText[5] : $cde_name;
                $cdeObj->andWhere('"code" like :searchText or UPPER("name") like :cde_name or UPPER("name") like :cde_used_name or UPPER("name") like :cde_used_name2 or UPPER("name") like :cde_used_name3 or UPPER("name") like :cde_used_name4 or UPPER("name") like :cde_used_name5 or UPPER("company") like :searchText or UPPER("clinical_indication") like :searchText', [':cde_name' => '%' . $cde_name . '%', ':cde_used_name' => '%' . $cde_used_name . '%', ':cde_used_name2' => '%' . $cde_used_name2 . '%', ':cde_used_name3' => '%' . $cde_used_name3 . '%', ':cde_used_name4' => '%' . $cde_used_name4 . '%', ':cde_used_name5' => '%' . $cde_used_name5 . '%', ':searchText' => '%' . $searchText[6] . '%']);
            }else{
				$cdeObj->andWhere('"code" like :searchText or UPPER("name") like :searchText or UPPER("company") like :searchText or UPPER("clinical_indication") like :searchText', [':searchText' => '%' . $searchText . '%']);
			}
			
        }

        //查找cde_favorite中的cde_id
        $cdeObj ->innerJoin('cde_favorite', '"cde_favorite"."cde_id"="cde"."id"');
        $cdeObj->andWhere('"cde_favorite"."user_id"=:user_id', [':user_id' => $uid]);
        
        $num = $cdeObj->count();

        $cde = $cdeObj->select(['decode("row_status", 0 , 1 , 0) "flag","cde"."id","code","company","join_date","name","rank","rank_status","row_status","indications_types"."ephmra_atc_code"'])->orderBy('"flag", "row_status"')->limit($pageSize)->offset($start)->asArray()->all();


        foreach ($cde as &$one) {
            $showRemark = '';
            $one['remark1'] = '';
            $one['custom_remark'] = '';
            foreach ($one['publicremark'] as $premark) {
                if (!empty($premark['public_remark'])) {
                    $showRemark .= $premark['user_id'] . ':' . $premark['public_remark'] . "<br/>";
                }
                if ($uid == $premark['user_id']) {
					$one['remark1'] = empty($premark['public_remark']) ? '' : $premark['public_remark'];
                    $one['custom_remark'] = empty($premark['remark']) ? '' : $premark['remark'];
                }
            }
            $one['showremark'] = $showRemark;
            if (empty($one['ephmra_atc_code'])) {
                $one['ephmra_atc_code'] = '';
            }


            unset($one['publicremark']);
        }

        $obj = new \stdClass();
        $obj->totalRows = $num;
        $obj->curPage = $curPage;
        $obj->data = $cde;

        return $obj;
    }
    
    /**
     * @return \yii\db\ActiveQuery
     *  获取cde关联的cdeType数据
     */
    public function getRankList() {
        return $this->hasMany(CdeRankList::className(), ['"cde_id"' => 'id'])->orderBy('"rank" asc');
    }

    /**
     * @return \yii\db\ActiveQuery
     *  获取cde关联的cdeType数据
     */
    public function getPublicremark() {
        return $this->hasMany(CdePublicremark::className(), ['"cde_id"' => 'id'])->orderBy('"create_date" desc');
    }
}
