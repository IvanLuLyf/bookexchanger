<?php
require_once __DIR__ . '/../autoload.php';

use Qiniu\Auth;
use Qiniu\Storage\BucketManager;

$accessKey = $redis->get('qiniu_ak');
$secretKey = $redis->get('qiniu_sk');

//初始化Auth状态：
$auth = new Auth($accessKey, $secretKey);

//初始化BucketManager
$bucketMgr = new BucketManager($auth);

//你要测试的空间， 并且这个key在你空间中存在
$bucket = $redis->get('qiniu_bucket');
$key = $img;

//删除$bucket 中的文件 $key
$err = $bucketMgr->delete($bucket, $key);
echo "\n====> delete $key : \n";
if ($err !== null) {
    var_dump($err);
} else {
    echo "Success!";
}
