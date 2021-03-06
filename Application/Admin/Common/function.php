<?php

/**
 * 获取个人中心菜单
 */
function personalCenterMenuList(){
	//$menuList = S("PERSONAL_CENTER_MENU_LIST");
	$menuList=null;
	if(!$menuList){
		$user_id=sessionLogin();
		
		$beanName="Node";
		$order['sort']="id";
		$order['order']="asc";
		$example['order']=$order;
		 
		$example['relation']=false;
		$condition['n.type']=1;
		$condition['n.level']=3;
		$condition['ru.user_id']=$user_id;
		$example['condition']=$condition;
		
		$menuList=D($beanName)->roleNodes($example,$beanName);

		//S("PERSONAL_CENTER_MENU_LIST",$menuList,31536000);
	}
	return $menuList;
}

/**
 * 获取16位长度数字
 */
function getLengthNum($length){
    return rand(pow(10,($length-1)), pow(10,$length)-1);
}

/**
 * 生成配置文件
 */
function set_shadowsocks_file($ss_confg){

    $file = dirname(dirname(dirname(dirname(__FILE__)))).'/shadowsocks.json';
    return file_put_contents($file,$ss_confg);
}

