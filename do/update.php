<?php
require 'functions.php';
if($redis->hget('user:'.maoo_user_id(),'user_level')==10) :
$file = 'http://wp.mao10.com/mao10cms-update-'.DB_TYPE.'.zip';
$fileName = ROOT_PATH."/upload/update.zip";
$save_path = ROOT_PATH."/upload/update";
if (@is_dir($save_path) === false) {
	mkdir($save_path, 0777);
}
if (@is_writable($save_path) === false) {
	die("更新缓存目录没有写权限");
}
if (!is_writeable($fileName)) {
	@chmod($fileName, 0777);
};
file_put_contents($fileName, file_get_contents( $file ) );
$zip = new ZipArchive;
if($zip->open($fileName)===TRUE){
	$zip->extractTo($save_path);
	$zip->close();
	recurse_copy($save_path,ROOT_PATH);
	deldir($save_path);
	$text = '升级成功';
} else {
	echo '升级失败，请稍后尝试';
}
$url = $redis->get('site_url').'?m=admin&a=index&done='.$text;
else :
	$url = $redis->get('site_url').'?done=请迅速撤离危险区域';
endif;
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
