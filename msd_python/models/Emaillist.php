<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_mail".
 *
 * @property string $id
 * @property integer $user_id
 * @property integer $cde_id
 */
class Emaillist extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_mail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'cde_id'], 'required'],
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
    
    static function getList($curPage, $pageSize, $typeId, $serachText, $uid) {
        $start = ($curPage - 1) * $pageSize;

        $cdeObj = Cde::find()->leftJoin('indications_types', 'indications_types.id=cde.indication_id')->with('rankList')->with('publicremark');

        if (!empty($typeId)) {
            $cdeObj->andWhere('tid=:tid', [':tid' => $typeId]);
        }

        if (!empty($serachText)) {
            $cdeObj->andWhere("code like :serachText or name like :serachText or company like :serachText", [':serachText' => '%' . $serachText . '%']);
        }

        //查找user_mail中的cde_id
        $cdeObj ->innerJoin('user_mail', 'user_mail.cde_id=cde.id');
        $cdeObj->andWhere('user_mail.user_id=:uid', [':uid' => $uid]);
        
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
