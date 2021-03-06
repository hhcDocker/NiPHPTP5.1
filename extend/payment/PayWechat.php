<?php
/**
 * 微信支付
 *
 * @package   NiPHPCMS
 * @category  extend\payment\
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @link      www.NiPHP.com
 * @since     2017/01/03
 */

namespace payment;

class PayWechat
{
    // 支付配置
    protected $config = [];

    protected $params = [];

    /**
     * 微信支付配置信息
     * @access public
     * @param  array  $config
     * @return void
     */
    public function __construct($_config)
    {
        $this->config = [
            'appid'        => $_config['appid'],
            'appsecret'    => $_config['appsecret'],
            'mch_id'       => $_config['mch_id'],
            'key'          => $_config['key'],
            'sign_type'    => !empty($_config['sign_type']) ? $_config['sign_type'] : 'md5',
            'sslcert_path' => $_config['sslcert_path'],
            'sslkey_path'  => $_config['sslkey_path'],
        ];
    }

    /**
     * 支付
     * @access public
     * @param  array $_params
     * @return mixed
     */
    public function transfer($_params)
    {
        $_params = array(
            'openid'       => '用户openid',
            // NO_CHECK：不校验真实姓名 FORCE_CHECK：强校验真实姓名
            'check_name'   => '校验用户姓名',
            // check_name为FORCE_CHECK时必填
            're_user_name' => '收款用户姓名',
            'amount'       => '金额',
            'desc'         => '企业付款描述信息',
        );
        $this->params = $_params;

        $this->params['mch_appid']        = $this->config['appid'];
        $this->params['mchid']            = $this->config['mch_id'];
        $this->params['nonce_str']        = $this->getNonceStr(32);
        $this->params['partner_trade_no'] = $this->orderNo();
        $this->params['spbill_create_ip'] = $_SERVER['REMOTE_ADDR'];
        $this->params['sign']             = $this->getSign($this->params);

        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
        $response = $this->postXmlCurl($this->toXml(), $url, true);
        $result = $this->formXml($response);
    }

    /**
     * 发送红包
     * @access public
     * @param  array  $_params 支付参数
     * @return string JS
     */
    public function sendBonus($_params)
    {
        $this->params = $_params;

        $this->params['nonce_str']  = $this->getNonceStr(32);
        $this->params['mch_billno'] = $this->orderNo();
        $this->params['mch_id']     = $this->config['mch_id'];
        $this->params['wxappid']    = $this->config['appid'];
        $this->params['client_ip']  = $_SERVER['REMOTE_ADDR'];
        $this->params['sign']       = $this->getSign($this->params);

        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
        $response = $this->postXmlCurl($this->toXml(), $url, true);
        $result = $this->formXml($response);

        if ($result['result_code'] == 'SUCCESS' && $result['err_code'] == 'SUCCESS') {
            $return = true;
        } else {
            $return = $result;
        }

        return $return;
    }

    /**
     * H5支付
     * @param  array $params
     * @return mixed
     */
    public function H5Pay($params)
    {
        // 同步通知回调地址
        $respond_url = $params['respond_url'];
        unset($params['respond_url']);

        $this->params = $params;
        $this->params['trade_type']  = 'MWEB';  // 交易类型
        $this->params['device_info'] = 'WEB';

        $result = $this->unifiedOrder();

        if ($result['return_code'] === 'FAIL') {
            return $result['return_msg'];
        } else {
            return $result['mweb_url'] . '&redirect_url=' . urlencode($respond_url);
        }
    }

    /**
     * 统一下单
     * @access public
     * @param  array  $_params 支付参数
     * @return string JS
     */
    public function jsPay($_params)
    {
        // 同步通知回调地址
        $respond_url = $_params['respond_url'];
        unset($_params['respond_url']);

        $this->params = $_params;
        $this->params['trade_type']  = 'JSAPI';  // 交易类型
        $this->params['device_info'] = 'WEB';

        $result = $this->unifiedOrder();

        // 新请求参数
        $params = [
            'appId'     => $result['appid'],
            'timeStamp' => (string) time(),
            'nonceStr'  => $this->getNonceStr(32),
            'package'   => 'prepay_id=' . $result['prepay_id'],
            'signType'  => strtoupper($this->config['sign_type']),
        ];

        $params['paySign'] = $this->getSign($params);
        $js_api_parameters = json_encode($params);

        return [
            'js_api_parameters' => $js_api_parameters,
            'notify_url' => $this->params['notify_url'],
            'js' => '<script type="text/javascript">function jsApiCall(){WeixinJSBridge.invoke("getBrandWCPayRequest",' . $js_api_parameters . ',function(res){if (res.err_msg == "get_brand_wcpay_request:ok") {window.location.replace("' . $respond_url . '?out_trade_no=' . $this->params['out_trade_no'] . '");} else if (res.err_msg == "get_brand_wcpay_request:cancel") {}});}function callpay(){if (typeof WeixinJSBridge == "undefined"){if( document.addEventListener ){document.addEventListener("WeixinJSBridgeReady", jsApiCall, false);}else if (document.attachEvent){document.attachEvent("WeixinJSBridgeReady", jsApiCall);document.attachEvent("onWeixinJSBridgeReady", jsApiCall);}}else{jsApiCall();}}</script>',
        ];
    }

    /**
     * 二维码支付
     * @access public
     * @param  array  $params 支付参数
     * @return string 二维码图片地址
     */
    public function qrcodePay($_params)
    {
        // 同步通知回调地址
        $respond_url = $_params['respond_url'];
        unset($_params['respond_url']);

        $this->params = $_params;
        $this->params['trade_type']  = 'NATIVE';  // 交易类型
        $this->params['device_info'] = 'WEB';

        $result = $this->unifiedOrder();
        if ($result['return_code'] === 'FAIL') {
            return $result['return_msg'];
        } else {
            $code_url = urlencode($result['code_url']);
            return 'http://paysdk.weixin.qq.com/example/qrcode.php?data=' . $code_url;
        }
    }

    /**
     * 同步通知回调
     * @access public
     * @param
     * @return mexid
     */
    public function respond()
    {
        $return = false;

        if (!empty($_GET['out_trade_no'])) {
            $out_trade_no = $_GET['out_trade_no'];
            $result = $this->queryOrder(['out_trade_no' => $out_trade_no]);
            if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS' && $result['trade_state'] == 'SUCCESS') {
                $return = [
                    'out_trade_no'   => $result['out_trade_no'],    // 商户订单号
                    'openid'         => $result['openid'],          // 支付人OPENID
                    'total_fee'      => $result['total_fee'],       // 支付金额
                    'trade_type'     => $result['trade_type'],      // 支付类型
                    'transaction_id' => $result['transaction_id'],  // 微信订单号
                ];
            }
        }

        return $return;
    }

    /**
     * 异步通知回调
     * @access public
     * @param
     * @return mexid
     */
    public function notify()
    {
        $return = false;

        if (!empty($GLOBALS['HTTP_RAW_POST_DATA'])) {
            $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
            $result = $this->formXml($xml);
            if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS') {
                $result = $this->queryOrder(['out_trade_no' => $result['out_trade_no']]);
                if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS' && $result['trade_state'] == 'SUCCESS') {
                    $return = [
                        'out_trade_no'   => $result['out_trade_no'],    // 商户订单号
                        'openid'         => $result['openid'],          // 支付人OPENID
                        'total_fee'      => $result['total_fee'],       // 支付金额
                        'trade_type'     => $result['trade_type'],      // 支付类型
                        'transaction_id' => $result['transaction_id'],  // 微信订单号
                    ];
                }
            }
        }

        return $return;
    }

    /**
     * 退款操作
     * @access public
     * @param
     * @return mixed
     */
    public function refund($_params)
    {
        $this->params = $_params;

        $this->params['appid']         = $this->config['appid'];
        $this->params['mch_id']        = $this->config['mch_id'];
        $this->params['nonce_str']     = $this->getNonceStr(32);
        $this->params['out_refund_no'] = $this->orderNo();
        $this->params['op_user_id']    = $this->config['mch_id'];
        $this->params['sign']          = $this->getSign($this->params);

        $url = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
        $response = $this->postXmlCurl($this->toXml(), $url, true);
        $return = $this->formXml($response);

        if ($result['result_code'] == 'SUCCESS') {
            $return = true;
        } else {
            $return = false;
        }

        return $return;
    }

    /**
     * 获得退款信息
     * @access public
     * @param
     * @return mixed
     */
    public function queryRefund($_params)
    {
        $this->params['appid']     = $this->config['appid'];
        $this->params['mch_id']    = $this->config['mch_id'];
        $this->params['nonce_str'] = $this->getNonceStr(32);

        if (!empty($_params['transaction_id'])) {
            $this->params['transaction_id'] = $_params['transaction_id'];
        }

        if (!empty($_params['out_trade_no'])) {
            $this->params['out_trade_no'] = $_params['out_trade_no'];
        }

        if (empty($this->params['transaction_id'])) {
            unset($this->params['transaction_id']);
        }

        if (empty($this->params['out_trade_no'])) {
            unset($this->params['out_trade_no']);
        }

        $this->params['sign'] = $this->getSign($this->params);

        $url = 'https://api.mch.weixin.qq.com/pay/refundquery';
        $response = $this->postXmlCurl($this->toXml(), $url);
        $result = $this->formXml($response);
        return $result;
    }

    /**
     * 获得订单信息
     * @access public
     * @param
     * @return mixed
     */
    public function queryOrder($_params)
    {
        $this->params['appid']     = $this->config['appid'];
        $this->params['mch_id']    = $this->config['mch_id'];
        $this->params['nonce_str'] = $this->getNonceStr(32);

        if (!empty($_params['transaction_id'])) {
            $this->params['transaction_id'] = $_params['transaction_id'];
        }

        if (!empty($_params['out_trade_no'])) {
            $this->params['out_trade_no'] = $_params['out_trade_no'];
        }

        if (empty($this->params['transaction_id'])) {
            unset($this->params['transaction_id']);
        }

        if (empty($this->params['out_trade_no'])) {
            unset($this->params['out_trade_no']);
        }

        $this->params['sign'] = $this->getSign($this->params);

        $url = 'https://api.mch.weixin.qq.com/pay/orderquery';
        $response = $this->postXmlCurl($this->toXml(), $url);
        $result = $this->formXml($response);
        return $result;
    }

    /**
     * 生成支付临时订单
     * @access private
     * @param
     * @return array
     */
    private function unifiedOrder()
    {
        $this->params['appid']            = $this->config['appid'];
        $this->params['mch_id']           = $this->config['mch_id'];
        $this->params['nonce_str']        = $this->getNonceStr(32);
        $this->params['spbill_create_ip'] = $_SERVER['REMOTE_ADDR'];
        $this->params['time_start']       = date('YmdHis');
        $this->params['time_expire']      = date('YmdHis', time() + 600);
        $this->params['sign']             = $this->getSign($this->params);

        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        $response = $this->postXmlCurl($this->toXml(), $url);
        $result = $this->formXml($response);
        return $result;
    }

    /**
     * 将array转为xml
     * @access private
     * @param
     * @return array
     */
    private function toXml()
    {
        $xml = '<xml>';
        foreach ($this->params as $key => $value) {
            if ($value != '' && !is_array($value)) {
                $xml .= '<' . $key . '>' . $value . '</' . $key . '>';
            }
        }
        $xml .= '</xml>';

        return $xml;
    }

    /**
     * 将xml转为array
     * @access private
     * @param  string $_xml
     * @return array
     */
    private function formXml($_xml)
    {
        libxml_disable_entity_loader(true);
        $data = (array) simplexml_load_string($_xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        return $data;
    }

    /**
     * 以post方式提交xml到对应的接口url
     * @access private
     * @param  string  $_xml      需要post的xml数据
     * @param  string  $_url      请求地址url
     * @param  boolean $_use_cert 是否使用证书
     * @param  intval  $_second   url执行超时时间，默认30s
     * @return mixed
     */
    private function postXmlCurl($_xml, $_url, $_use_cert = false, $_second = 30)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_TIMEOUT, $_second);       // 设置超时
        curl_setopt($curl, CURLOPT_URL, $_url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);      // 严格校验
        curl_setopt($curl, CURLOPT_HEADER, false);          // 设置header
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);   // 要求结果为字符串且输出到屏幕上
        if($_use_cert == true){
            //设置证书 使用证书：cert 与 key 分别属于两个.pem文件
            curl_setopt($curl, CURLOPT_SSLCERTTYPE, 'PEM');
            curl_setopt($curl, CURLOPT_SSLCERT, $this->config['sslcert_path']);
            curl_setopt($curl, CURLOPT_SSLKEYTYPE, 'PEM');
            curl_setopt($curl, CURLOPT_SSLKEY, $this->config['sslkey_path']);
        }
        curl_setopt($curl, CURLOPT_POST, true);             // post提交方式
        curl_setopt($curl, CURLOPT_POSTFIELDS, $_xml);      // post传输数据
        $result = curl_exec($curl);                         // 运行curl

        if ($result) {
            curl_close($curl);
            return $result;
        } else {
            $error = curl_errno($curl);
            curl_close($curl);
            return 'curl出错，错误码:' . $error;
        }
    }

    /**
     * 生成签名
     * @access private
     * @param  array $_params
     * @return 加密签名
     */
    private function getSign($_params)
    {
        ksort($_params);

        $sign = '';
        foreach ($_params as $key => $value) {
            if (!in_array($key, ['sign', 'sslcert_path']) && !is_array($value) && $value != '') {
                $sign .= $key . '=' . $value . '&';
            }
        }
        $sign .= 'key=' . $this->config['key'];
        $sign = trim($sign, '&');
        $sign = $this->config['sign_type']($sign);

        return strtoupper($sign);
    }

    /**
     * 产生随机字符串，不长于32位
     * @access private
     * @param  intval $_length
     * @return 产生的随机字符串
     */
    private function getNonceStr($_length = 32)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $count = strlen($chars) -1;
        $string = '';
        for ($i=0; $i < $_length; $i++) {
            $string .= substr($chars, mt_rand(0, $count), 1);
        }
        return $string;
    }

    /**
     * 生成订单号
     * @access private
     * @param
     * @return string
     */
    private function orderNo()
    {
        list($micro, $time) = explode(' ', microtime());
        $micro = str_pad($micro * 1000000, 6, 0, STR_PAD_LEFT);
        return substr($time, 0, 7) . date('YmdHis') . $micro . mt_rand(11111, 99999);
    }
}
