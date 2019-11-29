<?php

namespace console\models;
use common\models\DataAll;
use frontend\models\Product;
use Yii;
use \Exception;
class GatherJincheng extends Gather
{
    public $url = STOCKET_URL;
    public $kurl = STOCKET_KURL;

    public $ng = [];

    public function __construct(array $config = [])
    {
        parent::__construct($config);

    }


    public function run()
     {

         $products = Product::find()->where(['state' => 1, 'on_sale' => 1])->asArray()->all();
         foreach($products as $k=>$v){
             if($v['identify']=='btc'){
                 $url = 'https://www.bitstamp.net/api/v2/ticker/btcusd?time='.time();
             }elseif($v['identify']=='ltc'){
                 $url = 'https://www.bitstamp.net/api/v2/ticker/ltcusd?time='.time();
             }elseif ($v['identify']=='eth'){
                 $url = 'https://www.bitstamp.net/api/v2/ticker/ethusd?time='.time();
             }elseif ($v['identify']=='bch'){
                 $url = 'https://www.bitstamp.net/api/v2/ticker/bchusd?time='.time();
             }
             elseif ($v['identify'] == 'sz399300'){
                 $url = "http://web.sqt.gtimg.cn/q=".$v['identify']."?r=0.".time()*88;
             }elseif ($v['identify'] == 'lls'){
                 $url = "https://m.sojex.net/api.do?rtp=GetQuotesDetail&id=13";
             }elseif (in_array($v['identify'],$this->ng)){
                 $url = WEB_STOCKET_URL2.$v['identify'];
             }else{
                 $url = 'http://hq.sinajs.cn/etag.php?_='.time().'1000&list='.$v['identify'];
             }
             $curl = curl_init();
             curl_setopt($curl, CURLOPT_URL, $url);
             curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
             curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
             curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
             $result = curl_exec($curl);curl_close($curl);

             if (in_array($v['identify'],$this->ng)){

                 $result = gzdecode($result);
                 $result = json_decode($result,1);
                 $resultarr = $result[0];
             }else{
                 $resultarr = explode(',', $result);
             }

             if(in_array($v['identify'], array('hf_CL','hf_SI', 'hf_GC', 'hf_SI', 'hf_NG','hf_JY', 'hf_EC', 'hf_HG', 'hf_CHA50CFD', 'hf_CAD', 'hf_HSI'))){

                 $price = explode('"', $resultarr[0])[1];
                 $diff = $price - $resultarr[7];
                 if($diff == 0) {
                     $diff_rate = 0.00;
                 } else {
                     $diff_rate = number_format($diff / $resultarr[7] * 100, 2, ".", "");
                 }
                 $data = [
                     'price' => $price,
                     'open' => $resultarr[8],
                     'high' => $resultarr[4],
                     'low' => $resultarr[5],
                     'close' => $resultarr[7],
                     'diff' => $diff,
                     'diff_rate' => $diff_rate,
                     'time' => date('Y-m-d H:i:s'),
                     'symbol'=>$v['identify'],
                     'bp'           => $resultarr[2],
                     'sp'           => $resultarr[3],
                     'bv'           => $resultarr[10],
                     'sv'           => $resultarr[11],
                     'date'         =>time()
                 ];
             }elseif ($v['identify'] == 'nf_BU0' || $v['identify'] == 'nf_RB0'){

                 $data = [
                     'price' => $resultarr[7],
                     'open' => $resultarr[2],
                     'high' => $resultarr[3],
                     'low' => $resultarr[4],
                     'close' => $resultarr[10],
                     'diff' => $resultarr[6]-$resultarr[10],
                     'diff_rate' => ($resultarr[6]-$resultarr[10])/$resultarr[10]*100,
                     'time' => date('Y-m-d H:i:s'),
                     'symbol'=>$v['identify'],
                     'bp'           => $resultarr[7],
                     'sp'           => $resultarr[8],
                     'bv'           => $resultarr[11],
                     'sv'           => $resultarr[12],
                     'date'         =>time()
                 ];
             }elseif (in_array($v['identify'],$this->ng)){
                 $data = [
                     'price' => $resultarr[NewPrice],
                     'open' => $resultarr[Open],
                     'high' => $resultarr[High],
                     'low' => $resultarr[Low],
                     'close' => $resultarr[LastClose],
                     'diff' => '',
                     'diff_rate' => $resultarr[PriceChangeRatio],
                     'time' => date('Y-m-d H:i:s'),
                     'symbol'=>$v['identify'],
                     'bp'           => $resultarr[BP1],
                     'sp'           => $resultarr[SP1],
                     'bv'           => $resultarr[BV1],
                     'sv'           => $resultarr[SV1],
                     'date'         =>$resultarr[Date]
                 ];

             }elseif ($v['identify'] == 'hkHSI'){

                 $price = $resultarr[6];
                 $diff = $resultarr[7];
                 $diff_rate = $resultarr[8];
                 $dtime = strtotime($resultarr[sizeof($resultarr) - 2] ." " .explode('"', $resultarr[sizeof($resultarr) - 1])[0]);
                 $data = [
                     'price' => $price,
                     'open' => $resultarr[2],
                     'high' => $resultarr[4],
                     'low' => $resultarr[5],
                     'close' => $resultarr[3],
                     'diff' => $diff,
                     'diff_rate' => $diff_rate,
                     'time' => date('Y-m-d H:i:s', time()),
                     'symbol'=>$v['identify'],
                     'bp'           => $price,
                     'sp'           => $price,
                     'bv'           => rand(1,100),
                     'sv'           => rand(1,100),
                     'date'         =>$dtime
                 ];
             }
                 if (cache('risk'.$v['table_name']))
                 {
                     if (cache('now_point'.$v['table_name'])){
                         $data['price'] = cache('now_point'.$v['table_name'])+cache('risk'.$v['table_name']);
                         cache('now_point'.$v['table_name'],$data['price']);
                     }else{
                         $data['price'] = $data['price']+cache('risk'.$v['table_name']);
                         cache('now_point'.$v['table_name'],$data['price']);
                     }
                 }
               $res = self::dbUpdate('data_all', $data, ['symbol' =>$v['identify']]);
               $this->uniqueInsert($v['table_name'],$data);
               $this->listen();
         }
}


    public function kdata()
    {
                $obj = new Product();
		foreach($this->productList as $k => $v){

            $k_params = [
                'u'      => STOCKET_USER,
                'type'   => 'kline',
                'symbol' => $v,
                'line' =>'min,1',
                'num' => '1'
            ];
            $kline_data = $this->sendRequest($this->url, $k_params, 'GET', []);//k线数据

            if ($kline_data['ret']){
                $data3[$k] = gzdecode($kline_data['msg']);
                $k_data =  json_decode($data3[$k],true);

            }
	       }
    }

    public function curlfun($url, $params = array(), $method = 'GET')
    {
        $header = array();
        $opts = array(CURLOPT_TIMEOUT => 10, CURLOPT_RETURNTRANSFER => 1, CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false, CURLOPT_HTTPHEADER => $header);
        /* 根据请求类型设置特定参数 */
        switch (strtoupper($method)) {
            case 'GET' :
                $opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
                $opts[CURLOPT_URL] = substr($opts[CURLOPT_URL],0,-1);
                break;
            case 'POST' :
                //判断是否传输文件
                $params = http_build_query($params);
                $opts[CURLOPT_URL] = $url;
                $opts[CURLOPT_POST] = 1;
                $opts[CURLOPT_POSTFIELDS] = $params;
                break;
            default :
        }
        /* 初始化并执行curl请求 */
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $data = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        if($error){
            $data = null;
        }
        return $data;
    }

    public function getNewClose($symbol){
        $k_params = [
            'market'      => $symbol,
            'type'   => '1day',
            'size'=>2
        ];
        $kline_data = $this->sendRequest(STOCKET_KURL, $k_params, 'GET', []);//k线数据
        $kline_data = $kline_data['msg'];
        $k_data = json_decode($kline_data,1);
        $yes_close = $k_data['data'][0][4];
        if ($yes_close){
            return $yes_close;
        }else{
            $yes_close = DataAll::find()->where(['symbol'=>$symbol])->select('close')->one();
            $yes_close = $yes_close['close'];
            return $yes_close;
        }

    }


    /**
     * CURL发送Request请求,含POST和REQUEST
     *
     * @param string $url     请求的链接
     * @param mixed  $params  传递的参数
     * @param string $method  请求的方法
     * @param mixed  $options CURL的参数
     *
     * @return array
     */
    public function sendRequest($url, $params = [], $method = 'POST', $options = [])
    {
        $method       = strtoupper($method);
        $protocol     = substr($url, 0, 5);
        $query_string = is_array($params) ? http_build_query($params) : $params;

        $ch       = curl_init();
        $defaults = [];
        if ('GET' == $method) {
            $geturl                = $query_string ? $url . (stripos($url,
                    "?") !== false ? "&" : "?") . $query_string : $url;
            $defaults[CURLOPT_URL] = $geturl;
			//echo $geturl;
        } else {
            $defaults[CURLOPT_URL] = $url;
            if ($method == 'POST') {
                $defaults[CURLOPT_POST] = 1;
            } else {
                $defaults[CURLOPT_CUSTOMREQUEST] = $method;
            }
            $defaults[CURLOPT_POSTFIELDS] = $query_string;
        }

        $defaults[CURLOPT_HEADER]         = "utf-8";
        $defaults[CURLOPT_USERAGENT]      = "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.98 Safari/537.36";
        $defaults[CURLOPT_FOLLOWLOCATION] = true;
        $defaults[CURLOPT_RETURNTRANSFER] = true;
        $defaults[CURLOPT_CONNECTTIMEOUT] = 10;
        $defaults[CURLOPT_TIMEOUT]        = 10;

        // disable 100-continue
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Expect:']);

        if ('https' == $protocol) {
            $defaults[CURLOPT_SSL_VERIFYPEER] = false;
            $defaults[CURLOPT_SSL_VERIFYHOST] = false;
        }

        curl_setopt_array($ch, (array) $options + $defaults);

        $ret = curl_exec($ch);
        $err = curl_error($ch);

        if (false === $ret || ! empty($err)) {
            $errno = curl_errno($ch);
            $info  = curl_getinfo($ch);
            curl_close($ch);

            return [
                'ret'   => false,
                'errno' => $errno,
                'msg'   => $err,
                'info'  => $info,
            ];
        }
        curl_close($ch);



        return [
            'ret' => true,
            'msg' => $ret,
        ];
    }



}
