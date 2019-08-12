<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/7
 * Time: 17:11
 */
namespace common\components;
use yii\base\Behavior;
use yii\helpers\HtmlPurifier;
use yii\web\Controller;
use yii\web\Response;
class Filter extends Behavior{

    public function events()
    {
        return [
            //����������ִ��ǰ�����¼�������returnData����
            Controller::EVENT_BEFORE_ACTION => 'filterData',
        ];
    }

    //��������
    public function filterData()
    {
         $get =  \Yii::$app->request->get();
         $post = \Yii::$app->request->post();
         if (!empty($get)){
                array_filter($get,function(&$item1){
                    $item1 =intval($item1);
             });
         }

        if (!empty($post)){
            array_filter($post,function(&$item2){

                $item2 =htmlspecialchars($item2);
            });
        }
    }


}