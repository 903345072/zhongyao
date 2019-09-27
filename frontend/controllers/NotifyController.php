<?php

namespace frontend\controllers;

use Yii;
use yii\base\Controller;
use yii\log\FileTarget;
use frontend\models\UserCharge;
use frontend\models\User;

class NotifyController extends Controller
{

    public function actionOursNotify()
    {
        $log = new FileTarget();
        $log->logFile = Yii::getAlias('@givemoney/recharge.log');
        $returnArray = array( // 返回字段
            "out_trade_no" => $_REQUEST["out_trade_no"], // 商户ID
            "trade_status" =>  $_REQUEST["trade_status"], // 订单号
            "totalAmount" =>  $_REQUEST["totalAmount"], // 交易金额
            "sign" =>  $_REQUEST["sign"], // 交易时间
        );
        $md5key = "v63jjdJHtVq3exy6HyHEZ2D7cg23HrR1";
        $md5str = "orderAmount=".$returnArray['totalAmount']."&orderId=".$returnArray['out_trade_no'].'&priKey='.$md5key;
        $sign = strtoupper(md5($md5str));
        $log->messages[] = ['订单:'.$returnArray['out_trade_no'].'充值:'.$returnArray['totalAmount'].'签名:'.$_REQUEST["sign"].'校验签名:'.$sign,8,'application',time()];
        $log->export();
        if($_REQUEST["sign"] == $sign){
            if ($_REQUEST["trade_status"] == "TRADE_SUCCESS") {
                $userCharge = UserCharge::find()->where('trade_no = :trade_no', [':trade_no' => $returnArray['out_trade_no']])->one();
                if (!empty($userCharge)) {
                    //充值状态：1待付款，2成功，-1失败
                    if ($userCharge->charge_state == 1) {
                        //找到这个用户
                        $user = User::findOne($userCharge->user_id);
                        //给用户加钱
                        $user->account += $userCharge->amount;
                        if ($user->save()) {
                            //更新充值状态---成功
                            $userCharge->charge_state = 2;
                        }
                    }
                    //更新充值记录表
                    $res = $userCharge->update();
                    if ($res){
                        exit("success");
                    }
                }
            }
        }
    }

    public function actionYlNotify()      //亿联支付回调
    {
        $log = new FileTarget();
        $log->logFile = Yii::getAlias('@givemoney/recharge.log');
        $returnArray = array( // 返回字段
            "memberid" => $_REQUEST["memberid"], // 商户ID
            "orderid" =>  $_REQUEST["orderid"], // 订单号
            "amount" =>  $_REQUEST["amount"], // 交易金额
            "datetime" =>  $_REQUEST["datetime"], // 交易时间
            "transaction_id" =>  $_REQUEST["transaction_id"], // 流水号
            "returncode" => $_REQUEST["returncode"]
        );
        $md5key = "9g2mqyb1x8h9z72weh54xgw0rlxdjldn";
        ksort($returnArray);
        reset($returnArray);
        $md5str = "";
        foreach ($returnArray as $key => $val) {
            $md5str = $md5str . $key . "=" . $val . "&";
        }
        $sign = strtoupper(md5($md5str . "key=" . $md5key));
        $log->messages[] = ['订单:'.$returnArray['orderid'].'充值:'.$returnArray['amount'].'签名:'.$_REQUEST["sign"].'校验签名:'.$sign,8,'application',time()];
        $log->export();
        if($_REQUEST["sign"] == $sign){
            if ($_REQUEST["returncode"] == "00") {
                $userCharge = UserCharge::find()->where('trade_no = :trade_no', [':trade_no' => $returnArray['orderid']])->one();
                if (!empty($userCharge)) {
                    //充值状态：1待付款，2成功，-1失败
                    if ($userCharge->charge_state == 1) {
                        //找到这个用户
                        $user = User::findOne($userCharge->user_id);
                        //给用户加钱
                        $user->account += $userCharge->amount;
                        if ($user->save()) {
                            //更新充值状态---成功
                            $userCharge->charge_state = 2;
                        }
                    }
                    //更新充值记录表
                    $res = $userCharge->update();
                    if ($res){
                        exit("OK");
                    }
                }
            }
        }
    }

    public function actionOtoNotify()
    {
        $log = new FileTarget();
        $log->logFile = Yii::getAlias('@givemoney/recharge.log');
        $token = "IDEZIFC2L8Z96C7LRDDG819X8DLP6HTY";
        //回调过来的post值
        $bill_no = $_POST["bill_no"];                  //一个24位字符串，是此订单在020ZF服务器上的唯一编号
        $orderid = $_POST["orderid"];                  //是您在发起付款接口传入的您的自定义订单号
        $price = $_POST["price"];                      //单位：分。是您在发起付款接口传入的订单价格
        $actual_price = $_POST["actual_price"];        //单位：分。一定存在。表示用户实际支付的金额。
        $orderuid = $_POST["orderuid"];                //如果您在发起付款接口带入此参数，我们会原封不动传回。
        $key = $_POST["key"];
        $notify_key = md5($actual_price.$bill_no.$orderid.$orderuid.$price.$token);


        $log->messages[] = ['订单:'.$orderid.'充值:'.$price.'签名:'.$key.'校验签名:'.$notify_key,8,'application',time()];
        $log->export();

        if($key == $notify_key){
            //校验key成功，防止篡改,伪造。
            //在此位置编写成功支付后需要执行的逻辑，如加余额，订单付款成功，装备购买成功等等。
            //
            //
            $userCharge = UserCharge::find()->where('trade_no = :trade_no', [':trade_no' =>$orderid])->one();
            if (!empty($userCharge)) {
                //充值状态：1待付款，2成功，-1失败
                if ($userCharge->charge_state == 1) {
                    //找到这个用户
                    $user = User::findOne($userCharge->user_id);
                    //给用户加钱
                    $user->account += $actual_price/100;
                    if ($user->save()) {
                        //更新充值状态---成功
                        $userCharge->charge_state = 2;
                    }
                }
                //更新充值记录表
                $res = $userCharge->update();
                if ($res){
                    echo "success";	   //请不要修改或删除
                    return true;
                }
            }

        }
    }

    public function actionYxNotify()
    {


        if (isset($_POST['sign'])) {
            if ($_POST['sign'] === $this->getSign($_POST)) {
                if ($_POST['status'] == 1)
                {
                    $userCharge = UserCharge::find()->where('trade_no = :trade_no', [':trade_no' =>$_POST['trade_no']])->one();
                    if ($userCharge->charge_state == 1 && $userCharge->amount == $_POST['total_fee']/100) {
                        //找到这个用户
                        $user = User::findOne($userCharge->user_id);
                        //给用户加钱
                        $user->account += $_POST['total_fee']/100;
                        if ($user->save()) {
                            //更新充值状态---成功
                            $userCharge->charge_state = 2;
                        }
                        $res = $userCharge->update();
                        if ($res){
                            echo json_encode(['status'=>0,'message'=>'OK']);
                        }
                    }
                }
            }
        }
        return false;

    }

    private function getSign($params)
    {
        $signPars = "";
        ksort($params);
        foreach ($params as $k => $v) {
            if ("sign" !== $k && "" !== trim($v) && $v !== null) {
                $signPars .= $k . "=" . $v . "&";
            }
        }
        $signPars .= "key=" . 'e368b7bf9cf1c62e335f5784c88ec057';
        $sign = strtoupper(md5($signPars));
        return $sign;
    }

}
