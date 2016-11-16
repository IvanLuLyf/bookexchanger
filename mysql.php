<?php
class Mao10Mysql {
	var $mysql;
	var $prefix;

	public function connect($host,$username,$password,$db) {
		$conn = mysql_connect($host,$username,$password);
		mysql_select_db($db,$conn);
		mysql_query("SET NAMES utf8",$conn);
		$this->mysql = $conn;
	}

	public function setprefix($dbprefix) {
		$this->prefix = $dbprefix;
	}

	public function close() {
		mysql_close($this->mysql);
	}

	public function multi() {
		//暂不支持
	}

	public function exec() {
		//暂不支持
	}

	public function query($sql) {
        if (mysql_query($sql,$this->mysql)) {
		    return true;
		} else {
		    return false;
		}
    }

    public function exists($key) {
	    $result = mysql_query("SELECT name FROM ".$this->prefix."string WHERE name='".maoo_magic_in($key)."'",$this->mysql);
		while ($row = mysql_fetch_row($result)) {
			$key_array[] = $row[0];
		}
	    $result = mysql_query("SELECT name FROM ".$this->prefix."set WHERE name='".maoo_magic_in($key)."'",$this->mysql);
		while ($row = mysql_fetch_row($result)) {
			$key_array[] = $row[0];
		}
	    $result = mysql_query("SELECT name FROM ".$this->prefix."zset WHERE name='".maoo_magic_in($key)."'",$this->mysql);
		while ($row = mysql_fetch_row($result)) {
			$key_array[] = $row[0];
		}
	    $result = mysql_query("SELECT name FROM ".$this->prefix."hash WHERE name='".maoo_magic_in($key)."'",$this->mysql);
		while ($row = mysql_fetch_row($result)) {
			$key_array[] = $row[0];
		}
	    $result = mysql_query("SELECT name FROM ".$this->prefix."list WHERE name='".maoo_magic_in($key)."'",$this->mysql);
		while ($row = mysql_fetch_row($result)) {
			$key_array[] = $row[0];
		}
		if(in_array($key, $key_array)) {
			return true;
		} else {
			return false;
		}
    }
    
    public function newupdate() {
        $result = mysql_query("SELECT name,content FROM ".$this->prefix."hash",$this->mysql);
        while ($row = mysql_fetch_row($result)) {
            $value = unserialize(maoo_magic_out($row[1]));
            if($value) :
                $value = maoo_serialize($value);
                $sql = "UPDATE ".$this->prefix."hash SET content='".maoo_magic_in($value)."' WHERE name='".maoo_magic_out($row[0])."'";
                mysql_query($sql,$this->mysql);
            endif;
		}
		mysql_free_result($result);
        $result = mysql_query("SELECT name,content FROM ".$this->prefix."list",$this->mysql);
        while ($row = mysql_fetch_row($result)) {
            $value = unserialize(maoo_magic_out($row[1]));
            if($value) :
                $value = maoo_serialize($value);
                $sql = "UPDATE ".$this->prefix."list SET content='".maoo_magic_in($value)."' WHERE name='".maoo_magic_out($row[0])."'";
                mysql_query($sql,$this->mysql);
            endif;
            
		}
		mysql_free_result($result);
    }
    
    public function keys($key) {
        $key = str_replace('*','',$key);
    	$result = mysql_query("SELECT name FROM ".$this->prefix."hash WHERE name like '".maoo_magic_in($key)."%'",$this->mysql);
        while ($row = mysql_fetch_row($result)) {
			$val_array[] = maoo_magic_out($row[0]);
		}
		mysql_free_result($result);
		return $val_array;
    }
    public function del($key) {
    	$tables = array('string','set','zset','hash','list');
    	foreach($tables as $table) :
    		$sql = "DELETE FROM ".$this->prefix.$table." WHERE name='".maoo_magic_in($key)."'";
				mysql_query($sql,$this->mysql);
			endforeach;
    }

    public function flush() {
    	$tables = array('string','set','zset','hash','list');
    	foreach($tables as $table) :
    		$sql = "DELETE FROM ".$this->prefix.$table." WHERE content=''";
			mysql_query($sql,$this->mysql);
		endforeach;
    }

    public function incr($key) {
	    $result = mysql_query("SELECT name,content FROM ".$this->prefix."string WHERE name='".maoo_magic_in($key)."'",$this->mysql);
		while ($row = mysql_fetch_row($result)) {
			$old_key = $row[0];
			$old_val = $row[1];
		}
		if($old_key!=$key) :
			$sql = "INSERT INTO ".$this->prefix."string (name,content) VALUES ('".maoo_magic_in($key)."','1')";
			if (mysql_query($sql,$this->mysql)) {
				return 1;
			} else {
			    return false;
			}
		else :
			$val = $old_val+1;
			$sql = "UPDATE ".$this->prefix."string SET content='".maoo_magic_in($val)."' WHERE name='".maoo_magic_in($key)."'";
			if (mysql_query($sql,$this->mysql)) {
				return $val;
			} else {
			    return false;
			}
		endif;
    }

	public function set($key,$val) {
		$result = mysql_query("SELECT name FROM ".$this->prefix."string WHERE name='".maoo_magic_in($key)."'",$this->mysql);
		while ($row = mysql_fetch_row($result)) {
			$old_key = $row[0];
		}
		if($old_key!=$key) :
			$sql = "INSERT INTO ".$this->prefix."string (name,content) VALUES ('".maoo_magic_in($key)."','".maoo_magic_in($val)."')";
			if (mysql_query($sql,$this->mysql)) {
				return true;
			} else {
			    return false;
			}
		else :
			$sql = "UPDATE ".$this->prefix."string SET content='".maoo_magic_in($val)."' WHERE name='".maoo_magic_in($key)."'";
			if (mysql_query($sql,$this->mysql)) {
				return true;
			} else {
			    return false;
			}
		endif;
	}

	public function get($key) {
		$result = mysql_query("SELECT content FROM ".$this->prefix."string WHERE name='".maoo_magic_in($key)."'",$this->mysql);
		while ($row = mysql_fetch_row($result)) {
			return maoo_magic_out($row[0]);
		}
	}
    
	public function sadd($key,$val) {
		$result = mysql_query("SELECT name,content FROM ".$this->prefix."set WHERE name='".maoo_magic_in($key)."' AND content='".maoo_magic_in($val)."'",$this->mysql);
		while ($row = mysql_fetch_row($result)) {
			$old_key = $row[0];
			$old_val = $row[1];
		}
		if($old_key) :
			if($old_val!=$val) :
				$sql = "INSERT INTO ".$this->prefix."set (name,content) VALUES ('".maoo_magic_in($key)."','".maoo_magic_in($val)."')";
				if (mysql_query($sql,$this->mysql)) {
					return true;
				} else {
					return false;
				}
			endif;
		else :
			$sql = "INSERT INTO ".$this->prefix."set (name,content) VALUES ('".maoo_magic_in($key)."','".maoo_magic_in($val)."')";
			if (mysql_query($sql,$this->mysql)) {
				return true;
			} else {
				return false;
			}
		endif;
	}

	public function scard($key) {
		$result = mysql_query("SELECT id FROM ".$this->prefix."set WHERE name='".maoo_magic_in($key)."'",$this->mysql);
		while ($row = mysql_fetch_row($result)) {
			$val_array[] = $row[0];
		}
		mysql_free_result($result);
		return count($val_array);
	}

	public function sismember($key,$val) {
		$result = mysql_query("SELECT id FROM ".$this->prefix."set WHERE name='".maoo_magic_in($key)."' AND content='".maoo_magic_in($val)."'",$this->mysql);
		while ($row = mysql_fetch_row($result)) {
			$id = $row[0];
		}
		mysql_free_result($result);
		if($id>0) {
			return true;
		} else {
			return false;
		}
	}

	public function smembers($key) {
		$result = mysql_query("SELECT content FROM ".$this->prefix."set WHERE name='".maoo_magic_in($key)."'",$this->mysql);
		while ($row = mysql_fetch_row($result)) {
			$val_array[] = maoo_magic_out($row[0]);
		}
		mysql_free_result($result);
		return $val_array;
	}

    public function srem($key,$val) {
    	$sql = "DELETE FROM ".$this->prefix."set WHERE name='".maoo_magic_in($key)."' AND content='".maoo_magic_in($val)."'";
		if (mysql_query($sql,$this->mysql)) {
			return true;
		} else {
			return false;
		}
    }
    
	public function zscore($key,$val) {
		$result = mysql_query("SELECT score FROM ".$this->prefix."zset WHERE name='".maoo_magic_in($key)."' AND content='".maoo_magic_in($val)."'",$this->mysql);
		while ($row = mysql_fetch_row($result)) {
			return maoo_magic_out($row[0]);
		}
	}
    
	public function zcard($key) {
		$result = mysql_query("SELECT id FROM ".$this->prefix."zset WHERE name='".maoo_magic_in($key)."'",$this->mysql);
		while ($row = mysql_fetch_row($result)) {
			$val_array[] = $row[0];
		}
		mysql_free_result($result);
		return count($val_array);
	}

	public function zrange($key,$n1,$n2) {
		if($n2<0) :
			$result = mysql_query("SELECT content FROM ".$this->prefix."zset WHERE name='".maoo_magic_in($key)."' ORDER BY score ASC LIMIT ".$n1.",99",$this->mysql);
		else :
			$pagesize = $n2-$n1+1;
			$result = mysql_query("SELECT content FROM ".$this->prefix."zset WHERE name='".maoo_magic_in($key)."' ORDER BY score ASC LIMIT ".$n1.",".$pagesize."",$this->mysql);
		endif;
		while ($row = mysql_fetch_row($result)) {
			$val_array[] = $row[0];
		}
		mysql_free_result($result);
		return $val_array;
	}

	public function zrevrange($key,$n1,$n2) {
		if($n2<0) :
			$result = mysql_query("SELECT content FROM ".$this->prefix."zset WHERE name='".maoo_magic_in($key)."' ORDER BY score DESC LIMIT ".$n1.",99",$this->mysql);
		else :
			$pagesize = $n2-$n1+1;
			$result = mysql_query("SELECT content FROM ".$this->prefix."zset WHERE name='".maoo_magic_in($key)."' ORDER BY score DESC LIMIT ".$n1.",".$pagesize."",$this->mysql);
		endif;
		while ($row = mysql_fetch_row($result)) {
			$val_array[] = $row[0];
		}
		mysql_free_result($result);
		return $val_array;
	}

	public function zadd($key,$score,$val) {
		$result = mysql_query("SELECT name,content FROM ".$this->prefix."zset WHERE name='".maoo_magic_in($key)."' AND content='".maoo_magic_in($val)."'",$this->mysql);
		while ($row = mysql_fetch_row($result)) {
			$old_key = maoo_magic_out($row[0]);
			$old_val = maoo_magic_out($row[1]);
		}
		if($old_key) :
			if($old_val!=$val) :
				$sql = "INSERT INTO ".$this->prefix."zset (name,score,content) VALUES ('".maoo_magic_in($key)."','".maoo_magic_in($score)."','".maoo_magic_in($val)."')";
				if (mysql_query($sql,$this->mysql)) {
					return true;
				} else {
					return false;
				}
			else :
				$sql = "UPDATE ".$this->prefix."zset SET score='".maoo_magic_in($score)."' WHERE name='".maoo_magic_in($key)."' AND content='".maoo_magic_in($val)."'";
				if (mysql_query($sql,$this->mysql)) {
					return true;
				} else {
					return false;
				}
			endif;
		else :
			$sql = "INSERT INTO ".$this->prefix."zset (name,score,content) VALUES ('".maoo_magic_in($key)."','".maoo_magic_in($score)."','".maoo_magic_in($val)."')";
			if (mysql_query($sql,$this->mysql)) {
				return true;
			} else {
				return false;
			}
		endif;
	}

	public function zincrby($key,$score,$val) {
		$result = mysql_query("SELECT name,content FROM ".$this->prefix."zset WHERE name='".maoo_magic_in($key)."' AND content='".maoo_magic_in($val)."'",$this->mysql);
		while ($row = mysql_fetch_row($result)) {
			$old_key = maoo_magic_out($row[0]);
			$old_val = maoo_magic_out($row[1]);
		}
		if($old_key) :
			if($old_val!=$val) :
				$sql = "INSERT INTO ".$this->prefix."zset (name,score,content) VALUES ('".maoo_magic_in($key)."','".maoo_magic_in($score)."','".maoo_magic_in($val)."')";
				if (mysql_query($sql,$this->mysql)) {
					return true;
				} else {
					return false;
				}
			else :
				$sql = "UPDATE ".$this->prefix."zset SET score='".maoo_magic_in($old_val+$score)."' WHERE name='".maoo_magic_in($key)."' AND content='".maoo_magic_in($val)."'";
				if (mysql_query($sql,$this->mysql)) {
					return true;
				} else {
					return false;
				}
			endif;
		else :
			$sql = "INSERT INTO ".$this->prefix."zset (name,score,content) VALUES ('".maoo_magic_in($key)."','".maoo_magic_in($score)."','".maoo_magic_in($val)."')";
			if (mysql_query($sql,$this->mysql)) {
				return true;
			} else {
				return false;
			}
		endif;
	}

	public function zrem($key,$val) {
    	$sql = "DELETE FROM ".$this->prefix."zset WHERE name='".maoo_magic_in($key)."' AND content='".maoo_magic_in($val)."'";
		if (mysql_query($sql,$this->mysql)) {
			return true;
		} else {
			return false;
		}
    }

	public function hdel($key,$hash_key) {
		$result = mysql_query("SELECT name,content FROM ".$this->prefix."hash WHERE name='".maoo_magic_in($key)."'",$this->mysql);
		while ($row = mysql_fetch_row($result)) {
			$old_key = $row[0];
			$old_val = maoo_unserialize( maoo_magic_out($row[1]) );
			unset($old_val[$hash_key]);
		}
		if($old_key==$key) :
			$sql = "UPDATE ".$this->prefix."hash SET content='".maoo_magic_in(maoo_serialize($old_val))."' WHERE name='".maoo_magic_in($key)."'";
			if (mysql_query($sql,$this->mysql)) {
				return true;
			} else {
				return false;
			}
		endif;
	}

	public function hget($key,$hash_key) {
		$result = mysql_query("SELECT content FROM ".$this->prefix."hash WHERE name='".maoo_magic_in($key)."'",$this->mysql);
		while ($row = mysql_fetch_row($result)) {
			$hash_val = maoo_unserialize( $row[0] );
		};
        return maoo_magic_out($hash_val[$hash_key]);
	}

	public function hgetall($key) {
		$result = mysql_query("SELECT content FROM ".$this->prefix."hash WHERE name='".maoo_magic_in($key)."'",$this->mysql);
		while ($row = mysql_fetch_row($result)) {
			$hash_val = maoo_unserialize( $row[0] );
		}
        return maoo_magic_out($hash_val);
	}

	public function hset($key,$hash_key,$hash_val) {
        $hash_key = maoo_magic_in($hash_key);
        $hash_val = maoo_magic_in($hash_val);
		$result = mysql_query("SELECT name,content FROM ".$this->prefix."hash WHERE name='".maoo_magic_in($key)."'",$this->mysql);
		while ($row = mysql_fetch_row($result)) {
			$old_key = $row[0];
			$old_val = maoo_unserialize( $row[1] );
			$old_val[$hash_key] = $hash_val;
		}
		if($old_key!=$key) :
			$val_array[$hash_key] = $hash_val;
			$sql = "INSERT INTO ".$this->prefix."hash (name,content) VALUES ('".maoo_magic_in($key)."','".maoo_serialize($val_array)."')";
			if (mysql_query($sql,$this->mysql)) {
				return true;
			} else {
			    return false;
			}
		else :
			$sql = "UPDATE ".$this->prefix."hash SET content='".maoo_serialize($old_val)."' WHERE name='".maoo_magic_in($key)."'";
			if (mysql_query($sql,$this->mysql)) {
				return true;
			} else {
				return false;
			}
		endif;
	}

	public function hmset($key,$val_array) {
        foreach($val_array as $hash_key=>$hash_val) :
            $hash_key = maoo_magic_in($hash_key);
            $hash_val = maoo_magic_in($hash_val);
            $new_val_array[$hash_key] = $hash_val;
        endforeach;
        $val_array = $new_val_array;
		$result = mysql_query("SELECT name,content FROM ".$this->prefix."hash WHERE name='".maoo_magic_in($key)."'",$this->mysql);
		while ($row = mysql_fetch_row($result)) {
			$old_key = $row[0];
			$old_val = maoo_unserialize($row[1]);
		};
		if($old_key!=$key) :
			$sql = "INSERT INTO ".$this->prefix."hash (name,content) VALUES ('".maoo_magic_in($key)."','".maoo_serialize($val_array)."')";
			if (mysql_query($sql,$this->mysql)) {
				return true;
			} else {
			    return false;
			}
		else :
			foreach($val_array as $hash_key=>$hash_val) :
				$old_val[$hash_key] = $hash_val;
			endforeach;
			$sql = "UPDATE ".$this->prefix."hash SET content='".maoo_serialize($old_val)."' WHERE name='".maoo_magic_in($key)."'";
			if (mysql_query($sql,$this->mysql)) {
				return true;
			} else {
			    return false;
			}
		endif;
	}

	public function llen($key) {
		$result = mysql_query("SELECT content FROM ".$this->prefix."list WHERE name='".maoo_magic_in($key)."'",$this->mysql);
		while ($row = mysql_fetch_row($result)) {
			$old_val = maoo_unserialize(maoo_magic_out($row[0]));
		}
		return count($old_val);
	}

	public function lpush($key,$val) {
		$result = mysql_query("SELECT name,content FROM ".$this->prefix."list WHERE name='".maoo_magic_in($key)."'",$this->mysql);
		while ($row = mysql_fetch_row($result)) {
			$old_key = $row[0];
			$old_val = maoo_unserialize(maoo_magic_out($row[1]));
		}
		if($old_key!=$key) :
			$val_array[0] = $val;
			$sql = "INSERT INTO ".$this->prefix."list (name,content) VALUES ('".maoo_magic_in($key)."','".maoo_magic_in(maoo_serialize($val_array))."')";
			if (mysql_query($sql,$this->mysql)) {
				return true;
			} else {
			    return false;
			}
		else :
			array_unshift($old_val,$val);
			$sql = "UPDATE ".$this->prefix."list SET content='".maoo_magic_in(maoo_serialize($old_val))."' WHERE name='".maoo_magic_in($key)."'";
			if (mysql_query($sql,$this->mysql)) {
				return true;
			} else {
			    return false;
			}
		endif;
	}

	public function rpush($key,$val) {
		$result = mysql_query("SELECT name,content FROM ".$this->prefix."list WHERE name='".maoo_magic_in($key)."'",$this->mysql);
		while ($row = mysql_fetch_row($result)) {
			$old_key = $row[0];
			$old_val = maoo_unserialize(maoo_magic_out($row[1]));
		}
		if($old_key!=$key) :
			$val_array[0] = $val;
			$sql = "INSERT INTO ".$this->prefix."list (name,content) VALUES ('".maoo_magic_in($key)."','".maoo_magic_in(maoo_serialize($val_array))."')";
			if (mysql_query($sql,$this->mysql)) {
				return true;
			} else {
			    return false;
			}
		else :
			array_push($old_val,$val);
			$sql = "UPDATE ".$this->prefix."list SET content='".maoo_magic_in(maoo_serialize($old_val))."' WHERE name='".maoo_magic_in($key)."'";
			if (mysql_query($sql,$this->mysql)) {
				return true;
			} else {
			    return false;
			}
		endif;
	}

	public function ltrim($key,$n1,$n2) {
		$result = mysql_query("SELECT name,content FROM ".$this->prefix."list WHERE name='".maoo_magic_in($key)."'",$this->mysql);
		while ($row = mysql_fetch_row($result)) {
			$old_key = $row[0];
			$old_val = maoo_unserialize(maoo_magic_out($row[1]));
		}
		if($old_key==$key) :
			$list_num = 0;
			foreach($old_val as $list_key=>$list_val) :
				if($list_key>=$n1 && $list_key<=$n2) :
					$val_array[$list_num] = $list_val;
					$list_num++;
				endif;
			endforeach;
			$sql = "UPDATE ".$this->prefix."list SET content='".maoo_magic_in(maoo_serialize($val_array))."' WHERE name='".maoo_magic_in($key)."'";
			if (mysql_query($sql,$this->mysql)) {
				return true;
			} else {
			    return false;
			}
		endif;
	}

	public function lrange($key,$n1,$n2) {
		$result = mysql_query("SELECT name,content FROM ".$this->prefix."list WHERE name='".maoo_magic_in($key)."'",$this->mysql);
		while ($row = mysql_fetch_row($result)) {
			$old_key = $row[0];
			$old_val = maoo_unserialize(maoo_magic_out($row[1]));
		}
		if($old_key) :
			if($n2<0) :
				$n2 = 99;
			endif;
			$list_num = 0;
			foreach($old_val as $list_key=>$list_val) :
				if($list_key>=$n1 && $list_key<=$n2) :
					$val_array[$list_num] = $list_val;
					$list_num++;
				endif;
			endforeach;
			return $val_array;
		endif;
	}
    
	public function sort($key,$array=array('sort'=>'desc')) {
		//目前sort只对set类型数据有效
		if($array['limit']) :
			$result = mysql_query("SELECT content FROM ".$this->prefix."set WHERE name='".maoo_magic_in($key)."' ORDER BY (content+0) ".$array['sort']." LIMIT ".$array['limit'][0].",".$array['limit'][1]."",$this->mysql);
		else :
			$result = mysql_query("SELECT content FROM ".$this->prefix."set WHERE name='".maoo_magic_in($key)."' ORDER BY (content+0) ".$array['sort']."",$this->mysql);
		endif;
		while ($row = mysql_fetch_row($result)) {
            if(maoo_magic_out($row[0])>0) {
                $val_array[] = maoo_magic_out($row[0]);
            };
		}
		mysql_free_result($result);
		return $val_array;
	}

	public function expire() {
		//此函数暂时实现不了 数据库结构的悲剧
	}
}
