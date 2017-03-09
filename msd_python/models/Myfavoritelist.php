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

        $cdeObj = Cde::find()->leftJoin('indications_types', 'indications_types.id=cde.indication_id')->with('rankList')->with('publicremark');

        if (!empty($typeId)) {
            $cdeObj->andWhere('tid=:tid', [':tid' => $typeId]);
        }

        if (!empty($searchText)) {
            $cdeObj->andWhere("code like :searchText or name like :searchText or company like :searchText", [':searchText' => '%' . $searchText . '%']);
        }

        //查找cde_favorite中的cde_id
        $cdeObj ->innerJoin('cde_favorite', 'cde_favorite.cde_id=cde.id');
        $cdeObj->andWhere('cde_favorite.user_id=:uid', [':uid' => $uid]);
        
        $num = $cdeObj->count();

        $cde = $cdeObj->select('cde.id,code,company,join_date,name,rank,rank_status,row_status,indications_types.ephmra_atc_code')->orderBy('`row_status`!=0 DESC, row_status')->limit($pageSize)->offset($start)->asArray()->all();


        foreach ($cde as &$one) {
            $showRemark = '';
            $one['remark1'] = '';
            $one['custom_remark'] = '';
            foreach ($one['publicremark'] as $premark) {
                if (!empty($premark['public_remark'])) {
                    $showRemark .= $premark['uid'] . ':' . $premark['public_remark'] . "<br/>";
                }
                if ($uid == $premark['uid']) {
                    $one['remark1'] = $premark['public_remark'];
                    $one['custom_remark'] = $premark['remark'];
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
        return $this->hasMany(CdeRankList::className(), ['cde_id' => 'id'])->orderBy('rank asc');
    }

    /**
     * @return \yii\db\ActiveQuery
     *  获取cde关联的cdeType数据
     */
    public function getPublicremark() {
        return $this->hasMany(CdePublicremark::className(), ['cde_id' => 'id'])->orderBy('create_date desc');
    }
}
