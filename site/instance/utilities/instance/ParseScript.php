<?
class ParseScript{
	static $lastLine;
	static function parseText($text){
		$lines = explode("\n",$text);
		foreach($lines as $line){
			Debug::out($line);
			$line = preg_replace_callback('@\$\{([^}]*)\}@',array('preParse','parse'),$line);
			Debug::out($line);
			list($function,$data) = explode(' ',$line,2);
		
			if($function){
				Debug::out($function);
				self::$lastLine = call_user_func(array(self,$function),$data);
			}
		}
	}
	static function next($data){
		Debug::out($data);
		return $data;
	}
	//add entity
	static function add($data){
		list($name,$text,$keywords) = explode('|',$data);
		list($name,$context) = explode(':',$data);
		return Db::insert('entity',array(
				'name' => $name,
				'context' => $context,
				'keywords' => $keywords,
				'data' => $text,
				'time_created' => i()->Time()->datetime(),
				'time_updated' => i()->Time()->datetime(),
			));
	}
	static function relate($data){
		
	}
}

class preParse{
	static $vars = array();
	static function parse($match){
		$data = $match[1];
		if(preg_match('@\|@',$data)){
			list($function,$data) = explode('|',$data,2);
			return call_user_func_array(array(self,$function),explode(',',$data));
		}else{
			Debug::out(self::$vars);
			return self::$vars[$data];
		}
	}
	static function last($name){
		Debug::out(ParseScript::$lastLine);
		return self::$vars[$name] = ParseScript::$lastLine;
	}
	static function to($name,$value){
		return self::$vars[$name] = $value;
	}
}



/*
//search examples
name
name + context
descriptiong | keywords
instanceOf


zip files for upload and download
	-> extract into folder
*/