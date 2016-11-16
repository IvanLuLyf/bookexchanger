<?php
require __DIR__.'/../autoload.php';

define('CODE_KEY','ff1dcdfa0x02f41a');

function ch_num($num,$mode=true) {
  $char = array("零","壹","贰","叁","肆","伍","陆","柒","捌","玖");
  $dw = array("","拾","佰","仟","","萬","億","兆");
  $dec = "點";
  $retval = "";
  if($mode)
    preg_match_all("/^0*(\d*)\.?(\d*)/",$num, $ar);
  else
    preg_match_all("/(\d*)\.?(\d*)/",$num, $ar);
  if($ar[2][0] != "")
    $retval = $dec . ch_num($ar[2][0],false); //如果有小数，则用递归处理小数
  if($ar[1][0] != "") {
    $str = strrev($ar[1][0]);
    for($i=0;$i<strlen($str);$i++) {
      $out[$i] = $char[$str[$i]];
      if($mode) {
        $out[$i] .= $str[$i] != "0"? $dw[$i%4] : "";
        if($str[$i]+$str[$i-1] == 0)
          $out[$i] = "";
        if($i%4 == 0)
          $out[$i] .= $dw[4+floor($i/4)];
      }
    }
    $retval = join("",array_reverse($out)) . $retval;
  }
  return $retval;
};

function send_post($url, $post_data) {  
    $postdata = http_build_query($post_data); 
    $options = array(
        'http' => array(
            'method' => 'POST',
            'header' => 'Content-type:application/x-www-form-urlencoded',  
            'content' => $postdata,
            'timeout' => 15 * 60 // 超时时间（单位:s）
        )  
    );  
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    return $result;  
};

//序列化
function maoo_serialize($obj) {
   return base64_encode(gzcompress(serialize($obj)));
};
//反序列化
function maoo_unserialize($txt) {
   return unserialize(gzuncompress(base64_decode($txt)));
};

//判断程序是否安装
function maoo_is_install() {
    global $redis;
    if($redis->get('site_url') && $redis->get('page_size')>0 && $redis->get('fmimg')) :
        return true;
    else :
        return false;
    endif;
};

//项目剩余时间
function maoo_deal_remain_day($id) {
    global $redis;
    $time = $redis->hget('deal:'.$id,'deadline')-strtotime("now");
    $day = ($time-$time%86400)/86400;
    return $day;
};

//项目支持人数
function maoo_deal_reward_count($id) {
    global $redis;
    $count = 0;
    $rewards = unserialize($redis->hget('deal:'.$id,'reward'));
    foreach($rewards as $rewardkey=>$reward) :
        $count += $reward['count'];
    endforeach;
    return $count;
};

//项目进度百分比
function maoo_deal_percent($id) {
    global $redis;
    $goal = $redis->hget('deal:'.$id,'goal');
    $total = $redis->hget('deal:'.$id,'total');
    $percent = round($total/$goal,4)*100;
    return $percent;
};

//项目状态
function maoo_deal_status($id) {
    global $redis;
    $time = $redis->hget('deal:'.$id,'deadline')-strtotime("now");
    if($redis->sismember('deal_pending_id',$id)) :
        return 4;
    elseif(maoo_deal_percent($id)>=100) :
        return 2;
    elseif($time<0) :
        return 3;
    else :
        return 1;
    endif;
};

//判断是否启用阿里大鱼接口
function maoo_dayu() {
	global $redis;
	$sign = false;
	if($redis->get('user:connect:dayu:appkey') && $redis->get('user:connect:dayu:secretkey')) :
		$sign = true;
	endif;
	return $sign;
};

//积分抵现上限
function maoo_pay_coins_limit() {
	global $redis;
	$coins = $redis->get('coins:pay_coins_limit');
	if($coins>0) :
		return $coins;
	else :
		return 1000;
	endif;
};

//购买积分汇率
function maoo_cash_to_coins() {
	global $redis;
	$coins = $redis->get('coins:cash_to_coins');
	if($coins>0) :
		return $coins;
	else :
		return 10;
	endif;
};
//注册赠送积分
function maoo_coins_register() {
	global $redis;
	$coins = $redis->get('coins:register');
	if($coins>=0) :
		return $coins;
	else :
		return 3;
	endif;
};
//每日积分
function maoo_coins_every_day() {
	global $redis;
	$coins = $redis->get('coins:every_day');
	if($coins>=0) :
		return $coins;
	else :
		return 1;
	endif;
};
//下次登录获取积分时间
function maoo_coins_time($user_id) {
	global $redis;
	$user_coins_date = $redis->hget('user:'.$user_id,'user_coins_date')+86400-strtotime("now");
	if($user_coins_date>0) :
		$f=array(
        '3600'=>'小时',
        '60'=>'分钟',
        '1'=>'秒'
    );
    foreach ($f as $k=>$v)    {
        if (0 !=$c=floor($user_coins_date/(int)$k)) {
            return $c.$v.'后 可再次获得登录积分';
        }
    }
	else :
		return '可再次获得登录积分';
	endif;
};
//用户积分
function maoo_user_coins($user_id) {
	global $redis;
	$coins = $redis->hget('user:'.$user_id,'coins');
	if($coins>0) :
		return $coins;
	else :
		return 0;
	endif;
};
//用户现金余额
function maoo_user_cash($user_id) {
	global $redis;
	$coins = $redis->hget('user:'.$user_id,'cash');
	if($coins>0) :
		return $coins;
	else :
		return 0;
	endif;
};
//判断是否启用第三方登录
function maoo_social_sign() {
	global $redis;
	$sign = false;
	if($redis->get('user:connect:qq:appid') && $redis->get('user:connect:qq:appkey')) :
		$sign = true;
	elseif($redis->get('user:connect:weibo:appkey') && $redis->get('user:connect:weibo:appsecret')) :
		$sign = true;
	endif;
	return $sign;
};
//调用导航链接
function maoo_nav() {
	global $redis;
	$db = $redis->zrevrange('nav:list',0,99);
	if($db) :
		foreach($db as $page_id) : $number = $redis->zscore('nav:list',$page_id);
			$nav .= '<a class="nav-item" href="'.$redis->hget('nav:'.$page_id,'link').'">'.$redis->hget('nav:'.$page_id,'text').'</a>';
		endforeach;
	else :
		$nav .= '<a class="nav-item" href="'.maoo_url('post','latest').'">最新</a>';
		$nav .= '<a class="nav-item" href="'.maoo_url('index','authors').'">作者</a>';
		$nav .= '<a class="nav-item" href="'.maoo_url('bbs').'">社区</a>';
		$nav .= '<a class="nav-item" href="'.maoo_url('pro').'">商品</a>';
	endif;
	return $nav;
};
//调用友情链接
function maoo_link() {
	global $redis;
	$db = $redis->zrevrange('link:list',0,99);
	if($db) :
        $nav .= '<div class="link-box"><div class="container"><div class="link-box-in"><h4 class="title">友情链接：</h4>';
		foreach($db as $page_id) : $number = $redis->zscore('link:list',$page_id);
			$nav .= '<a class="link-item" target="_blank" href="'.$redis->hget('link:'.$page_id,'link').'">'.$redis->hget('link:'.$page_id,'text').'</a>';
		endforeach;
        $nav .= '</div></div></div>';
	endif;
	return $nav;
};
//调用友情链接
function maoo_ad($key) {
	global $redis;
	$db = $redis->hget('ad',$key);
	if($db) :
        $ad .= '<div class="mb-20">';
        $ad .= $db;
        $ad .= '</div>';
	endif;
	return $ad;
};
//判断移动设备
function maoo_is_mobile() {
	static $is_mobile;
	if ( isset($is_mobile) )
		return $is_mobile;
	if ( empty($_SERVER['HTTP_USER_AGENT']) ) {
		$is_mobile = false;
	} elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false // many mobile devices (all iPhone, iPad, etc.)
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false ) {
			$is_mobile = true;
	} else {
		$is_mobile = false;
	}
	return $is_mobile;
}
//当前页面链接
function maoo_page_url() {
	global $redis;
	if($_GET['m'] && $_GET['a']) :
		if($_GET['id']>0 && $_GET['type']) :
			$url = $redis->get('site_url').'?m='.$_GET['m'].'&a='.$_GET['a'].'&id='.$_GET['id'].'&type='.$_GET['type'];
		elseif($_GET['id']>0) :
			$url = $redis->get('site_url').'?m='.$_GET['m'].'&a='.$_GET['a'].'&id='.$_GET['id'];
		elseif($_GET['type']>0) :
			$url = $redis->get('site_url').'?m='.$_GET['m'].'&a='.$_GET['a'].'&type='.$_GET['type'];
		else :
			$url = $redis->get('site_url').'?m='.$_GET['m'].'&a='.$_GET['a'];
		endif;
	else :
		$url = $redis->get('site_url');
	endif;
	return $url;
};
//获取用户真实IP
function maoo_user_ip() {
	if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) {
		$ip = getenv("HTTP_CLIENT_IP");
	} elseif (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) {
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	} elseif (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) {
		$ip = getenv("REMOTE_ADDR");
	} elseif (isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) {
		$ip = $_SERVER['REMOTE_ADDR'];
	} else {
		$ip = "unknown";
	};
	return $ip;
};
//获取模板名称
function maoo_theme() {
	global $redis;
	//if(maoo_is_mobile()) :
		//return 'mobile';
	if($_SESSION['theme']) :
		$theme_array = array();
		$dir = __DIR__."/../theme"; if (is_dir($dir)) : if ($dh = opendir($dir)) : while (($file = readdir($dh))!= false) : $filePath = $dir.'/'.$file; if (is_dir($filePath)) : if($file!='.' && $file!='..' && $file!='admin' && $file!='mobile') :
			array_push($theme_array, $file);
		endif;endif; endwhile; closedir($dh); endif; endif;
		if(in_array($_SESSION['theme'], $theme_array)) :
			return $_SESSION['theme'];
		else :
			return 'default';
		endif;
	elseif($redis->get('theme')!='') :
		return $redis->get('theme');
	else :
		return 'default';
	endif;
};
//URL生成
function maoo_url($m='index',$a='index',$array=false) {
	global $redis;
	if($redis->get('rewrite')==2 && $m!='admin') :
		if($m=='post' || $m=='pro' || $m=='bbs' || $m=='deal') :
			if($a=='single' || $a=='topic' || $a=='term') :
				if($array) :
					foreach($array as $key=>$val) :
						if($key=='id') :
							$id = $val;
						else :
							$num++;
							if($num==1) :
								$url .= '?'.$key.'='.$val;
							else :
								$url .= '&'.$key.'='.$val;
							endif;
						endif;
					endforeach;
				endif;
				if($a=='single') :
					if($id>0) :
						return $redis->get('site_url').'/'.$m.'-'.$id.'.html'.$url;
					else :
						return $redis->get('site_url').'/'.$m.'-latest.html'.$url;
					endif;
				elseif($a=='topic') :
					if($id>0) :
						return $redis->get('site_url').'/'.$a.'-'.$id.'.html'.$url;
					else :
						return $redis->get('site_url').'/'.$m.'-'.$a.'.html'.$url;
					endif;
				elseif($a=='term') :
					if($id>0) :
						return $redis->get('site_url').'/'.$m.'-'.$a.'-'.$id.'.html'.$url;
					else :
						return $redis->get('site_url').'/'.$m.'-topic.html'.$url;
					endif;
				else :
					return $redis->get('site_url').'/'.$m.'-'.$a.'.html'.$url;
				endif;
			else :
				if($array) :
					foreach($array as $key=>$val) :
						$num++;
						if($num==1) :
							$url .= '?'.$key.'='.$val;
						else :
							$url .= '&'.$key.'='.$val;
						endif;
					endforeach;
				endif;
				return $redis->get('site_url').'/'.$m.'-'.$a.'.html'.$url;
			endif;
		elseif($m=='user' && $a=='index') :
			if($array) :
				foreach($array as $key=>$val) :
					if($key=='id') :
						$id = $val;
					else :
						$num++;
						if($num==1) :
							$url .= '?'.$key.'='.$val;
						else :
							$url .= '&'.$key.'='.$val;
						endif;
					endif;
				endforeach;
			endif;
			if($id>0) :
				return $redis->get('site_url').'/'.$m.'-'.$id.'.html'.$url;
			else :
				return $redis->get('site_url').'/'.$m.'-'.$a.'.html'.$url;
			endif;
		else :
			if($array) :
				foreach($array as $key=>$val) :
					$num++;
					if($num==1) :
						$url .= '?'.$key.'='.$val;
					else :
						$url .= '&'.$key.'='.$val;
					endif;
				endforeach;
			endif;
			return $redis->get('site_url').'/'.$m.'-'.$a.'.html'.$url;
		endif;
	else :
		if($array) :
			foreach($array as $key=>$val) :
				$url .= '&'.$key.'='.$val;
			endforeach;
		endif;
		return $redis->get('site_url').'/?m='.$m.'&a='.$a.$url;
	endif;
};
//截断
function maoo_cut_str($sourcestr,$cutlength) {
	$returnstr='';
	$i=0;
	$n=0;
	$str_length=strlen($sourcestr);//字符串的字节数
	while (($n<$cutlength) and ($i<=$str_length)) {
		$temp_str=substr($sourcestr,$i,1);
		$ascnum=Ord($temp_str);//得到字符串中第$i位字符的ascii码
		if ($ascnum>=224)    //如果ASCII位高与224，
		{
		$returnstr=$returnstr.substr($sourcestr,$i,3); //根据UTF-8编码规范，将3个连续的字符计为单个字符
		$i=$i+3;            //实际Byte计为3
		$n++;            //字串长度计1
		}
		elseif ($ascnum>=192) //如果ASCII位高与192，
		{
		$returnstr=$returnstr.substr($sourcestr,$i,2); //根据UTF-8编码规范，将2个连续的字符计为单个字符
		$i=$i+2;            //实际Byte计为2
		$n++;            //字串长度计1
		}
		elseif ($ascnum>=65 && $ascnum<=90) //如果是大写字母，
		{
		$returnstr=$returnstr.substr($sourcestr,$i,1);
		$i=$i+1;            //实际的Byte数仍计1个
		$n++;            //但考虑整体美观，大写字母计成一个高位字符
		}
		else                //其他情况下，包括小写字母和半角标点符号，
		{
		$returnstr=$returnstr.substr($sourcestr,$i,1);
		$i=$i+1;            //实际的Byte数计1个
		$n=$n+0.5;        //小写字母和半角标点等与半个高位字符宽…
		}
	}
	if ($str_length>$cutlength){
		$returnstr = $returnstr . '…';//超过长度时在尾处加上省略号
	}
	return $returnstr;
};
//获取当前登录的用户id
function maoo_user_id() {
	global $redis;
    if($_GET['uid']>0 && $_GET['token']) :
        $id = $_GET['uid'];
        $now = strtotime("now");
        if($redis->hget('user:'.$id,'token_deadline')<($now-86400*3)) :
            $redis->hset('user:'.$id,'token',maoo_rand(20));
            $redis->hset('user:'.$id,'token_deadline',$now);
            return false;
        else :
            if($_GET['token']==$redis->hget('user:'.$id,'token')) :
                $redis->hset('user:'.$id,'token_deadline',$now);
                return $id;
            else :
                return false;
            endif;
        endif;
    else :
        $user_name = $_SESSION['user_name'];
        if($user_name) {
            $id = $redis->zscore('user_id_name',$user_name);
            $user_pass = $redis->hget('user:'.$id,'user_pass');
            if($_SESSION['user_pass']==$user_pass) {
                if(maoo_user_ip()==$redis->hget('user:'.$id,'user_last_ip')) :
                    return $id;
                else :
                    return false;
                endif;
            } else {
                return false;
            };
        } else {
            return false;
        };
    endif;
};
//分页功能
function maoo_pagenavi($count,$page_now,$size=false) {
	global $redis;
	if($size) {
		$Page_size = $size;
	} elseif($redis->get('page_size')) {
		$Page_size = $redis->get('page_size');
	} else {
		$Page_size = 10;
	};
	$page_count = ceil($count/$Page_size);

	$init=1;
	$page_len=5;
	$max_p=$page_count;
	$pages=$page_count;

	//判断当前页码
	if(empty($page_now)||$page_now<0){
		$page=1;
	}else {
		$page=$page_now;
	};
	$offset = $Page_size*($page-1);
	$page_len = ($page_len%2)?$page_len:$pagelen+1;//页码个数
	$pageoffset = ($page_len-1)/2;//页码个数左右偏移量

	$key='<ul class="pagination">';
	$key.="<li class='pnum disabled'><a href='javascript:;'>$page/$pages</a></li>"; //第几页,共几页

	if($_GET['m'] && $_GET['a']) {
        if($_GET['id']) :
		  $page_url = $redis->get('site_url')."?m=".$_GET['m']."&a=".$_GET['a']."&id=".$_GET['id']."&";
        elseif($_GET['type']) :
		  $page_url = $redis->get('site_url')."?m=".$_GET['m']."&a=".$_GET['a']."&type=".$_GET['type']."&";
        elseif($_GET['s']) :
            $page_url = $redis->get('site_url')."?m=".$_GET['m']."&a=".$_GET['a']."&s=".$_GET['s']."&";
        else :
		  $page_url = $redis->get('site_url')."?m=".$_GET['m']."&a=".$_GET['a']."&";
        endif;
	} else {
        if($_GET['s'] && $_GET['type']) :
            $page_url = $redis->get('site_url')."?s=".$_GET['s']."&type=".$_GET['type']."&";
        elseif($_GET['s']) :
            $page_url = $redis->get('site_url')."?s=".$_GET['s']."&";
        else :
		  $page_url = $redis->get('site_url')."?";
        endif;
	}

	if($page!=1){
		$key.="<li class='first'><a href=\"".$page_url."page=1\">&laquo;</a></li>"; //第一页
		$key.="<li class='prev'><a href=\"".$page_url."page=".($page-1)."\">&lsaquo;</a></li>"; //上一页
	}else {
		$key.="<li class='first disabled'><a href='javascript:;'>&laquo;</a></li>";//第一页
		$key.="<li class='prev disabled'><a href='javascript:;'>&lsaquo;</a></li>"; //上一页
	}
	if($pages>$page_len){
		//如果当前页小于等于左偏移
		if($page<=$pageoffset){
			$init=1;
			$max_p = $page_len;
		}else{//如果当前页大于左偏移
			//如果当前页码右偏移超出最大分页数
			if($page+$pageoffset>=$pages+1){
				$init = $pages-$page_len+1;
			}else{
				//左右偏移都存在时的计算
				$init = $page-$pageoffset;
				$max_p = $page+$pageoffset;
			}
		}
	}
	for($i=$init;$i<=$max_p;$i++){
	if($i==$page){
		$key.='<li class="active"><a href="javascript:;">'.$i.'</a></li>';
	} else {
		$key.="<li><a href=\"".$page_url."page=".$i."\">".$i."</a></li>";
	}
	}
	if($page!=$pages){
		$key.="<li class='next'><a href=\"".$page_url."page=".($page+1)."\">&rsaquo;</a>";//下一页
		$key.="<li class='last'><a href=\"".$page_url."page={$pages}\">&raquo;</a></li>"; //最后一页
	}else {
		$key.='<li class="next disabled"><a href="javascript:;">&rsaquo;</a></li>';//下一页
		$key.='<li class="last disabled"><a href="javascript:;">&raquo;</a></li>'; //最后一页
	}
	$key.='</ul>';

	if($count>$Page_size) {
		return $key;
	}
};
//HTML危险标签过滤
function maoo_remove_html($text, $type = 'html') {
	if($type=='all') {
		$text = nl2br($text);
	    $text = real_strip_tags($text);
	    $text = addslashes($text);
	    $text = trim($text);
	} else {
		// 无标签格式
	    $text_tags = '';
	    //只保留链接
	    $link_tags = '<a>';
	    //只保留图片
	    $image_tags = '<img>';
	    //只存在字体样式
	    $font_tags = '<i><b><u><s><em><strong><font><big><small><sup><sub><bdo><h1><h2><h3><h4><h5><h6>';
	    //标题摘要基本格式
	    $base_tags = $font_tags . '<p><br><hr><a><img><map><area><pre><code><q><blockquote><acronym><cite><ins><del><center><strike>';
	    //兼容Form格式
	    $form_tags = $base_tags . '<form><input><textarea><button><select><optgroup><option><label><fieldset><legend>';
	    //内容等允许HTML的格式
	    $html_tags = $base_tags . '<ul><ol><li><dl><dd><dt><table><caption><td><th><tr><thead><tbody><tfoot><col><colgroup><span><object><embed><param>';
	    //专题等全HTML格式
	    $all_tags = $form_tags . $html_tags . '<!DOCTYPE><meta><html><head><title><body><base><basefont><script><noscript><applet><object><param><style><frame><frameset><noframes><iframe><div>';
	    //过滤标签
	    $text = real_strip_tags($text, ${$type . '_tags'});
	    // 过滤攻击代码
	    while (preg_match('/(<[^><]+)(ondblclick|onclick|onload|onerror|unload|onmouseover|onmouseup|onmouseout|onmousedown|onkeydown|onkeypress|onkeyup|onblur|onchange|onfocus|action|background|codebase|dynsrc|lowsrc)([^><]*)/i', $text, $mat)) {
            $text = str_ireplace($mat[0], $mat[1] . $mat[3], $text);
        }
        while (preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i', $text, $mat)) {
            $text = str_ireplace($mat[0], $mat[1] . $mat[3], $text);
        }
    }
    return $text;
}
function real_strip_tags($str, $allowable_tags = "")
{
    $str = html_entity_decode($str, ENT_QUOTES, 'UTF-8');
    return strip_tags($str, $allowable_tags);
};
//保存BASE64图片
function maoo_save_img_base64($img,$resize=false,$width=200,$height=200) {
	global $redis;
		$curDateTime = date("YmdHis");
		$ymd = date("Ym");
	    $randNum = rand(1000, 9999);
	    $out_trade_no = $curDateTime . $randNum;
	    $attached_type = '';
		if(strstr($img,'data:image/jpeg;base64,')) {
			$img_base = str_replace('data:image/jpeg;base64,', '', $img);
			$attached_type = 'jpg';
		} elseif(strstr($img,'data:image/png;base64,')) {
			$img_base = str_replace('data:image/png;base64,', '', $img);
			$attached_type = 'png';
		} elseif(strstr($img,'data:image/gif;base64,')) {
			$img_base = str_replace('data:image/gif;base64,', '', $img);
			$attached_type = 'gif';
		} else {
			return $img;
		};
		if($attached_type!='') {
			$img_decode = base64_decode($img_base);
			$fileName = ROOT_PATH.'/upload/image/'.$ymd.'/'.$out_trade_no.'.'.$attached_type; // 获取需要创建的文件名称
			$fileName_true = '/upload/image/'.$ymd.'/'.$out_trade_no.'.'.$attached_type;
			if (!is_dir(ROOT_PATH.'/upload/image/'.$ymd.'/')){
				mkdir(ROOT_PATH.'/upload/image/'.$ymd.'/', 0777); // 使用最大权限0777创建文件
			};
			if (!file_exists($fileName)) { // 如果不存在则创建
				// 检测是否有权限操作
				if (!is_writeable($fileName)) {
					@chmod($fileName, 0777); // 如果无权限，则修改为0777最大权限
				};
				// 最终将d写入文件即可
				file_put_contents($fileName, $img_decode);
				if($resize==true) {
					maoo_imagecropper($fileName,$width,$height);
				};
			};
			if($redis->get('upyun')==2) {
				require_once('upyun.class.php');
				$upyun = new UpYun($redis->get('upyun_bucket'), $redis->get('upyun_user'), $redis->get('upyun_pwd'));
				$fh = fopen($fileName, 'rb');
			    $rsp = $upyun->writeFile('/img/'.$ymd.'/'.$out_trade_no.'.'.$attached_type, $fh, True);
			    fclose($fh);
			    $file_url = $redis->get('upyun_url').'/img/'.$ymd.'/'.$out_trade_no.'.'.$attached_type;
			} elseif($redis->get('qiniu')==2) {
		    require_once __DIR__.'/qiniu/engine/upload.php';
		    $file_url = $redis->get('qiniu_url').'/'.$out_trade_no.'.'.$attached_type;
		  } else {
				$file_url = $redis->get('site_url').$fileName_true;
			};
			$redis->zadd('site_img_list',$curDateTime,$file_url);
			return $file_url;
		};
};
//替换正文BASE64图片链接
function maoo_str_replace_base64($content) {
	global $redis;
	preg_match_all("/\<img.*?src\=\"(.*?)\"[^>]*>/i", $content, $match);
	foreach($match[1] as $img) {
		$content = str_replace($img,maoo_save_img_base64($img),$content);
	};
	$content = str_replace("<p><br></p>","",$content);
	return $content;
};
//过滤转义字符
function maoo_magic_in($content) {
	if($content) :
		if(!get_magic_quotes_gpc()) :
		    $content = addslashes($content);
		endif;
	endif;
	return $content;
};
function maoo_magic_out($content) {
	$content1 = str_replace('\r\n', ' ', $content);
	$val = stripslashes($content1);
	return $val;
};
//生成随机字符串
function maoo_rand($length = 9) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $str = '';
    for ($i = 0; $i < $length; $i++) {
        $str .= $chars[mt_rand(0, strlen($chars) - 1)];
    }
    return $str;
};
//浏览统计
function maoo_get_views($id,$type='post') {
	global $redis;
	$count = $redis->hget($type.':'.$id,'views');
	if($count=='') {
		$count = 0;
	};
	return $count;
};
function maoo_set_views($id,$type='post') {
	global $redis;
	$count = $redis->hget($type.':'.$id,'views');
    if($redis->hget($type.':'.$id,'title')!='') :
        if($count=='') {
            $redis->hset($type.':'.$id,'views',1);
        } else {
            $redis->hset($type.':'.$id,'views',$count+1);
        };
        $redis->incr($type.'_views_incr');
    endif;
};
//喜欢按钮
function maoo_like_count($id) {
	global $redis;
	$count = $redis->hget('post:'.$id,'like_count');
	if($count=='') {
		$count = 0;
	};
	return $count;
};
function maoo_like_btn($id,$class='') {
	global $redis;
	if(maoo_user_id()) {
		if($redis->hget('post:'.$id,'author')!=maoo_user_id()) {
			$user_like = $redis->sismember('user_like:'.maoo_user_id(),$id);
			if($user_like) {
				$btn = '<a class="btn-like active maoo_like_'.$id.' '.$class.'" href="javascript:;"><i class="glyphicon glyphicon-heart"></i> <span>'.maoo_like_count($id).'</span></a>';
			} else {
				$btn = '<a class="btn-like maoo_like_'.$id.' '.$class.'" href="javascript:maoo_add_like('.$id.');"><i class="glyphicon glyphicon-heart"></i> <span>'.maoo_like_count($id).'</span></a>';
			};
		};
	} else {
		$btn = '<a class="btn-like maoo_like_'.$id.' '.$class.'" href="'.$redis->get('site_url').'?m=user&a=login"><i class="glyphicon glyphicon-heart"></i> <span>'.maoo_like_count($id).'</span></a>';
	};
	return $btn;
};
function maoo_like_js() {
	global $redis;
	if(maoo_user_id()) {
		$url = $redis->get('site_url').'/do/like.php?id=';
		$js = "<script>
		function maoo_add_like(id) {
			$.ajax({
				url: '".$url."' + id,
				type: 'GET',
				dataType: 'html',
				timeout: 9000,
				error: function() {
					alert('提交失败！');
				},
				success: function(html) {
					$('.maoo_like_'+id).attr('href','javascript:;');
					$('.maoo_like_'+id).addClass('active');
					$('.maoo_like_'+id).html('<i class=\'glyphicon glyphicon-heart\'></i> <span>已喜欢</span>');
				}
			});
		};
		</script>";
		return $js;
	};
};

//关注按钮
function maoo_guanzhu_count($id) {
	global $redis;
	$count = $redis->hget('user:'.$id,'guanzhu_count');
	if($count=='') {
		$count = 0;
	};
	return $count;
};
function maoo_guanzhu_btn($id,$class='') {
	global $redis;
	if(maoo_user_id()) {
		if($id!=maoo_user_id()) {
			$user_guanzhu = $redis->zscore('user_guanzhu:'.maoo_user_id(),$id);
			if($user_guanzhu>0) {
				$btn = '<a class="btn-guanzhu active '.$class.'" href="javascript:maoo_remove_guanzhu('.$id.');" id="maoo_guanzhu_'.$id.'"><i class="glyphicon glyphicon-star"></i> 取消关注 <span>'.maoo_guanzhu_count($id).'</span></a>';
			} else {
				$btn = '<a class="btn-guanzhu '.$class.'" href="javascript:maoo_add_guanzhu('.$id.');" id="maoo_guanzhu_'.$id.'"><i class="glyphicon glyphicon-star"></i> 关注 <span>'.maoo_guanzhu_count($id).'</span></a>';
			};
		};
	} else {
		$btn = '<a class="btn-guanzhu '.$class.'" href="'.$redis->get('site_url').'?m=user&a=login" id="maoo_guanzhu_'.$id.'"><i class="glyphicon glyphicon-star"></i> 关注 <span>'.maoo_guanzhu_count($id).'</span></a>';
	};
	return $btn;
};
function maoo_guanzhu_js() {
	global $redis;
	if(maoo_user_id()) {
		$js = "<script>
		function maoo_add_guanzhu(id) {
			$.ajax({
				url: '".$redis->get('site_url')."/do/add_guanzhu.php?id=' + id,
				type: 'GET',
				dataType: 'html',
				timeout: 9000,
				error: function() {
					alert('提交失败！');
				},
				success: function(html) {
					var count = $('#maoo_guanzhu_'+id+' span').text()*1+1;
					$('#maoo_guanzhu_'+id).attr('href','javascript:maoo_remove_guanzhu('+id+');');
					$('#maoo_guanzhu_'+id).addClass('active');
					$('#maoo_guanzhu_'+id).html('<i class=\'glyphicon glyphicon-star\'></i> 取消关注 <span>'+count+'</span>');
				}
			});
		};
		function maoo_remove_guanzhu(id) {
			$.ajax({
				url: '".$redis->get('site_url')."/do/remove_guanzhu.php?id=' + id,
				type: 'GET',
				dataType: 'html',
				timeout: 9000,
				error: function() {
					alert('提交失败！');
				},
				success: function(html) {
					var count = $('#maoo_guanzhu_'+id+' span').text()*1-1;
					$('#maoo_guanzhu_'+id).attr('href','javascript:maoo_add_guanzhu('+id+');');
					$('#maoo_guanzhu_'+id).removeClass('active');
					$('#maoo_guanzhu_'+id).html('<i class=\'glyphicon glyphicon-star\'></i> 关注 <span>'+count+'</span>');
				}
			});
		};
		</script>";
		return $js;
	};
};

//点赞按钮
function maoo_zan_btn($id) {
	global $redis;
	if(maoo_user_id()) {
        $zan = $redis->sismember('activity_zan_id:'.$id,maoo_user_id());
        if($zan) {
            $btn = '<a href="javascript:maoo_remove_zan('.$id.');" id="maoo_zan_'.$id.'" class="btn-zan"><i class="fa fa-heart"></i> 取消</a>';
        } else {
            $btn = '<a href="javascript:maoo_add_zan('.$id.');" id="maoo_zan_'.$id.'" class="btn-zan"><i class="fa fa-heart-o"></i> 点赞</a>';
        }
	} else {
		$btn = '<a href="'.$redis->get('site_url').'?m=user&a=login" id="maoo_zan_'.$id.'" class="btn-zan"><i class="fa fa-heart-o"></i> 点赞</a>';
	};
	return $btn;
};
function maoo_zan_js() {
	global $redis;
	if(maoo_user_id()) {
		$url = $redis->get('site_url').'/do/zan.php?id=';
		$js .= "<script>
		function maoo_add_zan(id) {
			$.ajax({
				url: '".$url."' + id,
				type: 'GET',
				dataType: 'html',
				timeout: 9000,
				error: function() {
					alert('提交失败！');
				},
				success: function(html) {
					$('#maoo_zan_'+id).attr('href','javascript:maoo_remove_zan('+id+');');
					$('#maoo_zan_'+id).html('<i class=\'fa fa-heart\'></i> 取消');
				}
			});
		};
		</script>";
		$js .= "<script>
		function maoo_remove_zan(id) {
			$.ajax({
				url: '".$url."' + id,
				type: 'GET',
				dataType: 'html',
				timeout: 9000,
				error: function() {
					alert('提交失败！');
				},
				success: function(html) {
					$('#maoo_zan_'+id).attr('href','javascript:maoo_add_zan('+id+');');
					$('#maoo_zan_'+id).html('<i class=\'fa fa-heart-o\'></i> 点赞');
				}
			});
		};
		</script>";
		return $js;
	};
};

//根据id获取分类名称
function maoo_term_title($id,$type='post') {
	global $redis;
	foreach($redis->zrange('term:'.$type,0,-1) as $title) :
		$key = $redis->zscore('term:'.$type,$title);
		if($key==$id) :
			return $title;
		endif;
	endforeach;
};
//更新用户最新列表
function maoo_refresh_user_list() {
	global $redis;
	$redis->del('new_user_id');
	foreach($redis->zrevrange('user_id_name',0,1200) as $user_name) :
		$user_id = $redis->zscore('user_id_name',$user_name);
		$redis->lpush('new_user_id',$user_id);
		$redis->ltrim('new_user_id',0,1199);
	endforeach;
};
 //剪裁图像
function maoo_imagecropper($source_path, $target_width, $target_height) {
	$source_info = getimagesize($source_path);
	$source_width = $source_info[0];
	$source_height = $source_info[1];
	$source_mime = $source_info['mime'];
	$source_ratio = $source_height / $source_width;
	$target_ratio = $target_height / $target_width;

	if(!file_exists($source_path)) {
		echo $source_path . " is not exists !";
		exit();
	}

	//$type=exif_imagetype($source_path);
	$support_type=array('image/gif' , 'image/jpeg' , 'image/png');
	if(!in_array($source_mime, $support_type,true)) {
		echo "this type of image does not support! only support jpg , gif or png";
		exit();
	}

	// 源图过高
	if ($source_ratio > $target_ratio)
	{
	$cropped_width = $source_width;
	$cropped_height = $source_width * $target_ratio;
	$source_x = 0;
	$source_y = ($source_height - $cropped_height) / 2;
	}
	// 源图过宽
	elseif ($source_ratio < $target_ratio)
	{
	$cropped_width = $source_height / $target_ratio;
	$cropped_height = $source_height;
	$source_x = ($source_width - $cropped_width) / 2;
	$source_y = 0;
	}
	// 源图适中
	else
	{
	$cropped_width = $source_width;
	$cropped_height = $source_height;
	$source_x = 0;
	$source_y = 0;
	}

	switch($source_mime) {
         case 'image/jpeg' :
         $source_image=imagecreatefromjpeg($source_path);
         break;
         case 'image/png' :
         $source_image=imagecreatefrompng($source_path);
         break;
         case 'image/gif' :
         $source_image=imagecreatefromgif($source_path);
         break;
         default:
         echo "Load image error!";
         exit();
         }

	$target_image = imagecreatetruecolor($target_width, $target_height);
	$cropped_image = imagecreatetruecolor($cropped_width, $cropped_height);

	// 裁剪
	imagecopy($cropped_image, $source_image, 0, 0, $source_x, $source_y, $cropped_width, $cropped_height);
	// 缩放
	imagecopyresampled($target_image, $cropped_image, 0, 0, 0, 0, $target_width, $target_height, $cropped_width, $cropped_height);

	//保存图片到本地
	switch($source_mime) {
         case 'image/jpeg' :
         imagejpeg($target_image, $source_path);
         break;
         case 'image/png' :
         imagepng($target_image,$source_path);
         break;
         case 'image/gif' :
         imagegif($target_image,$source_path);
         break;
         default:
         break;
         }
	imagedestroy($target_image);
	imagedestroy($source_image);
	imagedestroy($cropped_image);
};
//获取用户名
function maoo_user_display_name($id) {
	global $redis;
	if($id>0) {
		if($redis->hget('user:'.$id,'display_name')=='') {
			return $redis->hget('user:'.$id,'user_name').'#'.$id;
		} else {
			return $redis->hget('user:'.$id,'display_name').'#'.$id;
		}
	} else {
		return '匿名';
	}
};
//获取用户头像
function maoo_user_avatar($id) {
	global $redis;
	$avatar = $redis->hget('user:'.$id,'avatar');
	if($avatar=='') :
		$avatar = $redis->get('site_url').'/public/img/avatar.png';
	endif;
	return $avatar;
};
//获取封面图片
function maoo_fmimg($id,$type='post') {
	global $redis;
	$fmimg = $redis->hget($type.':'.$id,'fmimg');
	if($fmimg=='') {
        ob_start();
        ob_end_clean();
        $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $redis->hget($type.':'.$id,'content'), $matches);
        $first_img = $matches [1] [0];
        if(empty($first_img)){
            $fmimg = $redis->get('fmimg');
        } else {
            $fmimg = $first_img;
        }
    };
	if(strpos($fmimg,'http://')===0) {
		return $fmimg;
	} else {
		return $redis->get('site_url').$fmimg;
	};
};
//时间格式化
function maoo_format_date($time){
    $t=time()-$time;
    $f=array(
        '31536000'=>'年',
        '2592000'=>'个月',
        '604800'=>'星期',
        '86400'=>'天',
        '3600'=>'小时',
        '60'=>'分钟',
        '1'=>'秒'
    );
    foreach ($f as $k=>$v)    {
        if (0 !=$c=floor($t/(int)$k)) {
            return $c.$v.'前';
        }
    }
};
//商品最小价格
function maoo_pro_min_price($id) {
	global $redis;
	$price_array = array();
	$parameters = unserialize($redis->hget('pro:'.$id,'parameter'));
	foreach($parameters as $parameter) :
		if($parameter['price']>0) :
			array_push($price_array,$parameter['price']);
		endif;
	endforeach;
	if($redis->hget('pro:'.$id,'sale_off_date')>strtotime("now") && $redis->hget('pro:'.$id,'sale_off')>0) :
		return min($price_array)*$redis->hget('pro:'.$id,'sale_off')/10;
	else :
		return min($price_array);
	endif;
};
function maoo_pro_max_price($id) {
	global $redis;
	$price_array = array();
	$parameters = unserialize($redis->hget('pro:'.$id,'parameter'));
	foreach($parameters as $parameter) :
		if($parameter['price']>0) :
			array_push($price_array,$parameter['price']);
		endif;
	endforeach;
	if($redis->hget('pro:'.$id,'sale_off_date')>strtotime("now") && $redis->hget('pro:'.$id,'sale_off')>0) :
		return max($price_array)*$redis->hget('pro:'.$id,'sale_off')/10;
	else :
		return max($price_array);
	endif;
};
function maoo_pro_original_min_price($id) {
	global $redis;
	$price_array = array();
	$parameters = unserialize($redis->hget('pro:'.$id,'parameter'));
	foreach($parameters as $parameter) :
		if($parameter['price']>0) :
			array_push($price_array,$parameter['price']);
		endif;
	endforeach;
	return min($price_array);
};
function maoo_pro_original_max_price($id) {
	global $redis;
	$price_array = array();
	$parameters = unserialize($redis->hget('pro:'.$id,'parameter'));
	foreach($parameters as $parameter) :
		if($parameter['price']>0) :
			array_push($price_array,$parameter['price']);
		endif;
	endforeach;
	return max($price_array);
};
function maoo_update_stock($id,$name,$num) {
	global $redis;
	$parameters = unserialize($redis->hget('pro:'.$id,'parameter'));
	foreach($parameters as $key_par=>$parameter) :
		if($parameter['name']==$name) :
			$parameters[$key_par]['stock'] = $parameter['stock']+$num;
		endif;
	endforeach;
	$parameters_new = serialize($parameters);
	$redis->hset('pro:'.$id,'parameter',$parameters_new);
};
function maoo_comment_json($pid,$type) {
	global $redis;
	$comments = array();
		$db = $redis->sort($type.'_comment_id:'.$pid,array('sort'=>'desc','limit'=>array(0,100)));
		foreach($db as $comment) :
			$user_id = $redis->hget('comment:'.$comment,'author');
			$comobj->id = $comment;
			if($user_id>0) :
				$comobj->userLink = maoo_url('user','index',array('id'=>$user_id));
			else :
				$comobj->userLink = 'javascript:;';
			endif;
			$comobj->content = $redis->hget('comment:'.$comment,'content');
			$comobj->userId = $user_id;
			$comobj->userName = maoo_user_display_name($user_id);
			$comobj->userAvatar = maoo_user_avatar($user_id);
			$comobj->time = maoo_format_date($redis->hget('comment:'.$comment,'date'));
			$comobj->child = array();
			$db_child = $redis->sort('comment_child_id:'.$comment,array('sort'=>'desc','limit'=>array(0,100)));
			foreach($db_child as $comment_child) :
				$user_id_child = $redis->hget('comment:'.$comment_child,'author');
				$comobj_child->id = $comment_child;
				if($user_id>0) :
					$comobj_child->userLink = maoo_url('user','index',array('id'=>$user_id_child));
				else :
					$comobj_child->userLink = 'javascript:;';
				endif;
				$comobj_child->content = $redis->hget('comment:'.$comment_child,'content');
				$comobj_child->userId = $user_id_child;
				$comobj_child->userName = maoo_user_display_name($user_id_child);
				$comobj_child->userAvatar = maoo_user_avatar($user_id_child);
				$comobj_child->time = maoo_format_date($redis->hget('comment:'.$comment_child,'date'));
				$comobj_child->child = array();
				array_push($comobj->child, $comobj_child);
				unset($comobj_child);
			endforeach;
			array_push($comments, $comobj);
			unset($comobj);
		endforeach;
		return json_encode($comments);
};
//新增信息
function maoo_add_message($user,$text,$type=false) {
	global $redis;
	$date['content'] = $text;
	$date['author'] = $user;
	$date['date'] = strtotime("now");
	if($text && $user>0) :
		$id = $redis->incr('activity_id_incr');
        $date['id'] = $id;
        if($type) :
            $date['private'] = 1;
        endif;
        $redis->hmset('activity:'.$id,$date);
        $redis->sadd('activity_id',$id);
        $redis->sadd('user_activity_id:'.$user,$id);
	endif;
};
//文件覆盖
function recurse_copy($src,$des) {
    $dir = opendir($src);
    @mkdir($des);
    while(false !== ( $file = readdir($dir)) ) {
             if (( $file != '.' ) && ( $file != '..' ) && ( $file != '__MACOSX' )) {
                    if ( is_dir($src . '/' . $file) ) {
                            recurse_copy($src . '/' . $file,$des . '/' . $file);
                    }  else  {
                            copy($src . '/' . $file,$des . '/' . $file);
                    }
            }
    }
  	closedir($dir);
		unlink($src);
}
//文件删除
function deldir($dir) {
  $dh=opendir($dir);
  while ($file=readdir($dh)) {
    if($file!="." && $file!="..") {
      $fullpath=$dir."/".$file;
      if(!is_dir($fullpath)) {
          unlink($fullpath);
      } else {
          deldir($fullpath);
      }
    }
  }
  closedir($dh);
}
