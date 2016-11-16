<?php
/* PHP SDK
 * @version 2.0.0
 * @author connect@qq.com
 * @copyright © 2013, Tencent Corporation. All rights reserved.
 */

require_once(CLASS_PATH."ErrorCase.class.php");
require_once __DIR__."/../../../../do/functions.php";
class Recorder{
    private static $data;
    private $inc;
    private $error;

    public function __construct(){
        global $redis;
        $this->error = new ErrorCase();

        //-------读取配置文件
        $inc->appid = $redis->get('user:connect:qq:appid');
		$inc->appkey = $redis->get('user:connect:qq:appkey');
		$inc->callback = $redis->get('site_url').'/public/connect-qq';
		$inc->scope = 'get_user_info';
		$inc->errorReport = true;
		$inc->storageType = 'file';
		$inc->host = 'localhost';
		$inc->user = 'root';
		$inc->password = 'root';
		$inc->database = 'test';
		/*
        $incFileContents = file(ROOT."comm/inc.php");
        $incFileContents = $incFileContents[1];
        */
        $this->inc = $inc;
        if(empty($this->inc)){
            $this->error->showError("20001");
        }

        if(empty($_SESSION['QC_userData'])){
            self::$data = array();
        }else{
            self::$data = $_SESSION['QC_userData'];
        }
    }

    public function write($name,$value){
        self::$data[$name] = $value;
    }

    public function read($name){
        if(empty(self::$data[$name])){
            return null;
        }else{
            return self::$data[$name];
        }
    }

    public function readInc($name){
        if(empty($this->inc->$name)){
            return null;
        }else{
            return $this->inc->$name;
        }
    }

    public function delete($name){
        unset(self::$data[$name]);
    }

    function __destruct(){
        $_SESSION['QC_userData'] = self::$data;
    }
}
