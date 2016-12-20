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
