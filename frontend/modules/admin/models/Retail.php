<?php

namespace admin\models;

use Yii;

class Retail extends \common\models\Retail
{
    public $file1;
    public $file2;
    public $file3;
    public $file4;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['file1', 'file2', 'file3', 'file4'], 'file', 'extensions' => 'jpg, png', 'skipOnEmpty' => true, 'maxSize' => 1024 * 2 * 1000

            ],
            [['point','profit_point'], 'verifyPoint']
        ]);
    }

    public function verifyPoint($attribute, $params)
    {

              $point = $this->point;
              $profit_point = $this->profit_point;
              $pid = $this->created_by;
              if ($pid == 1){
                  return true;
              }
              $p_admin_id = \common\models\AdminUser::findOne($pid)->id;
              $p_retail_model = $this::find()->where(['admin_id'=>$p_admin_id])->one();
              if ($profit_point>$p_retail_model->profit_point || $point>$p_retail_model->point){
                  $this->addError($attribute,'返点百分比不得大于上级百分比');
              }
              return true;

    }
    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            // 'scenario' => ['field1', 'field2'],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'file1' => '法人身份证',
            'file2' => '营业执照',
            'file3' => '组织机构代码证',
            'file4' => '税务登记证',
            'point' => '手续费返点(%)',
            'profit_point' => '盈利返点(%)'
        ]);
    }
}
