<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件


/**
 * @desc 生成盐值
 * @rerurn string 盐值
 * @author 郑剑峰 <mail@codiy.net>
 */
function create_salt($length = 6) {
    $codes= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
    for($i=0; $i<$length; ++$i) {
        if($i == 0) {
            $salt = $codes[mt_rand(0,25)];
        } else {
            $salt .= $codes[mt_rand(0,61)];
        }
    }
    return $salt;
}

/**
 * @desc 检测是否是邮箱
 * @param string $user_email 邮箱
 * @return boolean
 * @author 郑剑峰 <mail@codiy.net>
 */
function is_email($user_email)
{
	$chars = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i";
	if (strpos($user_email, '@') !== false && strpos($user_email, '.') !== false)
	{
		if (preg_match($chars, $user_email))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}

/**
 * @desc 检测是否是手机号码
 * @param string $phonenumber 手机号码
 * @return boolean
 * @author 郑剑峰 <mail@codiy.net>
 */
function is_mobile($phonenumber){
	$chars = "/^1[0-9]{10}$/";
	if (preg_match($chars, $phonenumber)){
			return true;
	}
	else{
		return false;
	}
}

/**
 * @desc 字符串加密函数
 * @param string $string 待加密字符串
 * @param string $key 加密密钥
 * @return string
 * @author zhengjf <mail@codiy.net>
 * @since 2016-03-20
*/
function encrypt($string,$key='cds9w05sd') {
    $key=md5($key);
    $key_length=strlen($key);
    $string=substr(md5($string.$key),0,8).$string;
    $string_length=strlen($string);
    $rndkey=$box=array();
    $result='';
    for($i=0;$i<=255;$i++){
        $rndkey[$i]=ord($key[$i%$key_length]);
        $box[$i]=$i;
    }
    for($j=$i=0;$i<256;$i++){
        $j=($j+$box[$i]+$rndkey[$i])%256;
        $tmp=$box[$i];
        $box[$i]=$box[$j];
       $box[$j]=$tmp;
    }
    for($a=$j=$i=0;$i<$string_length;$i++){
       $a=($a+1)%256;
       $j=($j+$box[$a])%256;
        $tmp=$box[$a];
        $box[$a]=$box[$j];
        $box[$j]=$tmp;
        $result.=chr(ord($string[$i])^($box[($box[$a]+$box[$j])%256]));
    }
    $result = base64_encode($result);
    $result = str_replace('=','',$result);
    $result = str_replace('/','@',$result);
    $result = str_replace('+','$',$result);
    return $result;
}
/**
 * @desc 字符串解密函数
 * @param string $string 待解密字符串
 * @param string $key 解密密钥
 * @return string
 * @author zhengjf <mail@codiy.net>
 * @since 2016-03-20
 */
function decrypt ($string,$key = 'cds9w05sd') {
    $string = str_replace('@','/',$string);
    $string = str_replace('$','+',$string);
    $key=md5($key);
    $key_length=strlen($key);
    $string=base64_decode($string);
    $string_length=strlen($string);
    $rndkey=$box=array();
    $result='';
    for($i=0;$i<=255;$i++){
        $rndkey[$i]=ord($key[$i%$key_length]);
        $box[$i]=$i;
    }
    for($j=$i=0;$i<256;$i++){
        $j=($j+$box[$i]+$rndkey[$i])%256;
        $tmp=$box[$i];
        $box[$i]=$box[$j];
        $box[$j]=$tmp;
    }
    for($a=$j=$i=0;$i<$string_length;$i++){
        $a=($a+1)%256;
        $j=($j+$box[$a])%256;
        $tmp=$box[$a];
        $box[$a]=$box[$j];
        $box[$j]=$tmp;
        $result.=chr(ord($string[$i])^($box[($box[$a]+$box[$j])%256]));
    }
    if(substr($result,0,8)==substr(md5(substr($result,8).$key),0,8)){
        return substr($result,8);
    } else {
        return false;
    }
}
/**
 * @desc 封装CURL调用
 * @see cURL lib version > 7.16.2 php version > 5.2.3
 * @param string $url URL地址
 * @param array $paramArr 参数
 * @param string $method 请求方式 get|post
 * @param number $expiry 超时 毫秒 curl/php限制
 * @param string $isDebug 是否调试
 * @return Ambigous <string, mixed, array> 调试返回array('url' => '', 'return' => '')
 * @author 郑剑峰 <mail@codiy.net>
 */
function curl($url, $paramArr = array(), $method = "get", $expiry = 20, $isDebug = false) {
    //默认get方式请求
    $method == '' && $method = "get";
    //get方式 组织URL参数
    if ($method == 'get' && !empty($paramArr)) {
        $paramStr = '';
        foreach ($paramArr as $key => $value) {
            $paramStr .= "/{$key}/" . urlencode($value) . "/";
        }
        //去掉最后一个&字符
        $paramStr = substr($paramStr,0,count($paramStr)-2);

        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){
            $paramStr = stripslashes($paramStr);
        }
        $url .= $paramStr;
        //去除URL中//问题
        $url = str_replace("//", "/", $url);
    }

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    //连接超时
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT_MS, $expiry);
    //获取数据超时
    curl_setopt($curl, CURLOPT_TIMEOUT_MS, $expiry);
    //post方式 参数传递
    if ($method == 'post') {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS,$paramArr);
    }
    $result = curl_exec($curl);
    $info = curl_getinfo($curl);

    if ($result === false || $info['http_code'] != 200) {
        $result = "No cURL data returned for $url [". $info['http_code']. "]";
        if (curl_error($curl)) $result .= "\n". curl_error($curl);
    }

    curl_close($curl);
    unset($curl);
    if ($isDebug == true || $isDebug == 1) {
        $result = array('url' => $url, 'return' => $result);
    }
    return $result;
}


/**
 * @desc 随机字符串
 * @param string $type 类型number纯数字|string数字＋大小写字母|all数字＋大小写字母＋部分特殊字符
 * @param number $lenth 长度
 * @return string
 * @author 郑剑峰 <mail@codiy.net>
 */
function random($type = 'all', $lenth = 6) {
    if ($type == 'number') {
        $seeds = '1234567890';
        $seedsLenth = strlen($seeds) -1;
    } elseif ($type == 'string') {
        $seeds = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
        $seedsLenth = strlen($seeds) -1;
    } else {
        $seeds = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ~!@#$%^&*()_+-={}[]:";';
        $seedsLenth = strlen($seeds) -1;
    }
    $rand = "";
    for ($i = 0; $i < $lenth; $i++) {
        $rand .= $seeds{mt_rand (0, $seedsLenth)};
    }
    return $rand;
}



function downUrl($url, $saveFile, $offset = 0, $proxy = '', $httpHeader = '') {
	$h_curl = curl_init();
	$h_file = fopen($saveFile, 'wb');
	curl_setopt($h_curl, CURLOPT_HEADER, 0);
	curl_setopt($h_curl, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($h_curl, CURLOPT_TIMEOUT, 60);
	curl_setopt($h_curl, CURLOPT_URL, $url);
	curl_setopt($h_curl, CURLOPT_FILE, $h_file);
	curl_setopt($h_curl, CURLOPT_PROXY, $proxy);
	curl_setopt($h_curl, CURLOPT_SSL_VERIFYPEER, false); // 阻止对证书的合法性的检查
	curl_setopt($h_curl, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
	curl_setopt($h_curl, CURLOPT_RESUME_FROM, intval($offset));
	//curl_setopt($h_curl, CURLOPT_RETURNTRANSFER, true);
	if(!empty($httpHeader) && is_array($httpHeader)) {
		curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);
	}
	$curl_success = curl_exec($h_curl);

	fclose($h_file);
	curl_close($h_curl);
	return $curl_success;
}

/**
 * @desc 创建多级目录
 * @param string $dir 单级/多级目录路径
 * @param int $mode 权限
 * @return boolean
 * @author 郑剑峰 <mail@codiy.net>
 */
function mk_dir($dir, $mode = 0777) {
	if(is_dir($dir) || @mkdir($dir, $mode)) return true;
	if(!mk_dir(dirname($dir), $mode)) return false;
	return @mkdir($dir, $mode);
}


/**
 * @desc 字符串截取，支持中文和其他编码
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 * @return string
 * @author 郑剑峰 <mail@codiy.net>
 */
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) {
	if(function_exists('mb_substr')){
		if($suffix)
			return mb_substr($str, $start, $length, $charset) . '...';
		else
			return mb_substr($str, $start, $length, $charset);
	}
	elseif(function_exists('iconv_substr')) {
		if($suffix)
			return iconv_substr($str,$start,$length,$charset) . '...';
		else
			return iconv_substr($str,$start,$length,$charset);
	}
	$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
	$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
	$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
	$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
	preg_match_all($re[$charset], $str, $match);
	$slice = join('', array_slice($match[0], $start, $length));
	if($suffix) return $slice."…";
	return $slice;
}

/**
 * @desc 获取客户端IP
 * @return string IP地址
 * @author 郑剑峰 <mail@codiy.net>
 */
function getip(){
    $onlineip = '';
    if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $onlineip = getenv('HTTP_CLIENT_IP');
    } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $onlineip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $onlineip = getenv('REMOTE_ADDR');
    } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $onlineip = $_SERVER['REMOTE_ADDR'];
    }
    return $onlineip;
}

/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @return mixed
 */
function get_client_ip($type = 0) {
    $type       =  $type ? 1 : 0;
    static $ip  =   NULL;
    if ($ip !== NULL) return $ip[$type];
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos    =   array_search('unknown',$arr);
        if(false !== $pos) unset($arr[$pos]);
        $ip     =   trim($arr[0]);
    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip     =   $_SERVER['HTTP_CLIENT_IP'];
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip     =   $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u",ip2long($ip));
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}
