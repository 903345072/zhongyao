<?php

namespace frontend\models;

use Yii;
use common\helpers\FileHelper;

class UserCharge extends \common\models\UserCharge
{
    //翰银支付
    public static function hypay($amount, $pay_type = 'wangyin')
    {
        $amount = sprintf("%.2f", $amount);
        $user = User::findModel(u()->id);
        //保存充值记录
        $userCharge = new UserCharge();
        $userCharge->user_id = $user->id;
        $userCharge->trade_no = $user->id . date("YmdHis") . rand(1000, 9999);;
        $userCharge->amount = $amount;
        $userCharge->charge_type = 3;
        if ($pay_type == 'alipay') {
            $userCharge->charge_type = self::CHARGE_TYPE_ALIPAY;
        }
        $userCharge->charge_state = self::CHARGE_STATE_WAIT;
        if (!$userCharge->save()) {
            return false;
        }
        $url = 'http://www.hanppay.com/apisubmit';
        $data['version'] = '1.0'; //版本号
        $data['customerid'] = 12036; //商户id
        $data['sdorderno'] = $userCharge->trade_no;
        $data['total_fee'] = $amount;
        $data['paytype'] = $pay_type;
        $data['notifyurl'] =  url(['site/hynotify'], true); //异步回调地址
        $data['returnurl'] = url(['site/index'], true);
        $string = 'version='.$data['version'].'&customerid='.$data['customerid'].'&total_fee='.$data['total_fee'].'&sdorderno='.$data['sdorderno'].'&notifyurl='.$data['notifyurl'].'&returnurl='.$data['returnurl'].'&7a68ac64e17a0ba4e1e8c859f6df022399701710';
        $data['sign'] = md5($string);//验证签名    sign    必填，sign = MD5(merchant_no+total_fee+today+key)；
        $res = httpRequest($url, $data);
        echo $res;
        return false;
    }

    public static function ylpay($amount,$pay_type)     //亿联支付
    {
        error_reporting(0);
        header("Content-type: text/html; charset=utf-8");
        $amount = sprintf("%.2f", $amount);
        $user = User::findModel(u()->id);
        $userCharge = new self();
        $userCharge->user_id = $user->id;
        $userCharge->trade_no = $user->id . date("YmdHis") . rand(1000, 9999);;
        $userCharge->amount = $amount;
        $userCharge->charge_type = 3;
        if ($pay_type == 'alipay') {
            $userCharge->charge_type = self::CHARGE_TYPE_ALIPAY;
        }
        $userCharge->charge_state = self::CHARGE_STATE_WAIT;
        if (!$userCharge->save(0)) {
            return false;
        }
        $pay_memberid = "190802572";   //商户ID
        $pay_orderid = $userCharge->trade_no;    //订单号
        $pay_amount = $amount;    //交易金额
        $pay_applydate = date("Y-m-d H:i:s");  //订单时间
        $pay_notifyurl = ly_notify;   //服务端返回地址
        $pay_callbackurl = url(['site/index']);  //页面跳转返回地址
        $Md5key = "y8zes5689ug5pr2igw2b0rfzbet1r4wg";   //密钥
        $tjurl = "http://185.23.201.138/Pay_Index.html";   //提交地址
        $pay_bankcode = "911";   //银行编码
//扫码
        $native = array(
            "pay_memberid" => $pay_memberid,
            "pay_orderid" => $pay_orderid,
            "pay_amount" => $pay_amount,
            "pay_applydate" => $pay_applydate,
            "pay_bankcode" => $pay_bankcode,
            "pay_notifyurl" => $pay_notifyurl,
            "pay_callbackurl" => $pay_callbackurl,
        );
        ksort($native);
        $md5str = "";
        foreach ($native as $key => $val) {
            $md5str = $md5str . $key . "=" . $val . "&";
        }
        $sign = strtoupper(md5($md5str . "key=" . $Md5key));
        $native["pay_md5sign"] = $sign;
        $native['pay_attach'] = "1234|456";
        $native['pay_productname'] ='VIP基础服务';
        $str = '<form id="Form1" name="Form1" method="post" action="' . $tjurl . '">';
        foreach ($native as $key => $val) {
            $str = $str . '<input type="hidden" name="' . $key . '" value="' . $val . '">';
        }
        $str = $str . '<input type="submit" value="订单提交中">';
        $str = $str . '</form>';
        $str = $str . '<script>';
        $str = $str . 'document.Form1.submit();';
        $str = $str . '</script>';
        return $str;
    }
}

