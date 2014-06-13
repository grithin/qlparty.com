<?
class ViewTool{
	static $formFieldTypes = array(
			'phone'=>'text'
		);
	///used to apply formatting for display of form value.  PageTool functions will override if PageTool::$formFieldTypes[key] present.
	///first argument is type, the rest get passed on to the form function
	static function get(){
		$arguments = func_get_args();
		$type = array_shift($arguments);
		
		if(isset(PageTool::$formFieldTypes)  && PageTool::$formFieldTypes[$type]){
			Form::$valueCallbacks[] = array(PageTool,$type);
			$method = PageTool::$formFieldTypes[$type];
		}else{
			Form::$valueCallbacks[] = array(__class__,$type);
			$method = self::$formFieldTypes[$type];
		}
		$return = call_user_func_array(array('Form',$method),$arguments);
		array_pop(Form::$valueCallbacks);
		return $return;
	}
	
	static function phone(&$value){
		if(strlen($value) == 10){
			$areacode = substr($value,0,3);
			$part1 = substr($value,3,3);
			$part2 = substr($value,6);
			$value = '('.$areacode.') '.$part1.'-'.$part2;
		}
	}
	static function date(&$value){
		if($value && InputValidator::check('date',$value)){
			$value = i()->Time($value,Config::$x['timezone'])->setZone(Config::$x['inOutTimezone'])->date();
		}
	}
	static function conditionalBr2Nl($value){
		if(!preg_match('@<div|<p|<table@',$value)){
			$value = preg_replace('@<br ?/>@',"\n",$value);
		}
		return $value;
	}
	static function round($value,$round=2,$type='',$default=''){
		if($value == '-' || $value == null){
			return $default;
		}else{
			if($type == '%'){
				$value = $value * 100;
			}
			
			$return = number_format(round($value,$round),$round);
			
			if($type){
				if($type == '%'){
					$return = $return.'%';
				}else{
					$return = '$'.$return;
				}
			}
			return $return;
		}
	}
	static function limit($text,$wordSize=35,$totalText=null){
		///see php manual "Once-only subpatterns" for (.?.>.) explanation
		while(preg_match('@((?>[^\s]{'.$wordSize.'}))([^\s])@',$text)){
			$text = preg_replace('@((?>[^\s]{'.$wordSize.'}))([^\s])@','$1 $2',$text,1);
		}
		if($totalText && strlen($text) > $totalText){
			$text = '<span class="shortened" title="'.htmlspecialchars($text).'">'.htmlspecialchars(substr($text,0,$totalText)).'</span>';
		}
		return $text;
	}
	

	static function actor($name,$id){
		return '<a href="/actor/read/'.$id.'">'.self::limit($name).'</a>';
	}
	static function user($name,$id){
		return '<a href="/user/read/'.$id.'">'.self::limit($name).'</a>';
	}
	static function timeAgo($time){
		return '<span class="timeAgo">'.i()->Time($time)->unix().'</span>';
	}
	static function datetime($time,$timezone=null){
		if($timezone){
			return '<span class="datetime">'.i()->Time($time,Config::$x['timezone'])->datetime($timezone).'</span>';
		}else{
			return '<span class="unixtime">'.i()->Time($time)->unix().'</span>';
		}
		#return i()->Time($time,Config::$x['timezone'])->setZone(Config::$x['inOutTimezone'])->datetime();
	}
	static function humanDatetime($time,$timezone=null){
		return '<span class="datetime">'.i()->Time($time,Config::$x['timezone'])->format('F j, Y, g:i a',$timezone).'</span>';
	}
	
	static function datetimeIncludes(){
		View::addCss(
				'/public/resource/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css',
				'/public/resource/jquery-timepicker/timepicker.css'
			);
		View::addTopJs('/resource/jquery-ui/js/jquery-ui-1.10.3.custom.min.js');
		View::addBottomJs('/public/resource/jquery-timepicker/jquery-ui-timepicker-addon.js');
	}
}
