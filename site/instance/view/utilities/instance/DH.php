<?
//display helper
class DH{
	static function roundTo($value,$round=2,$type='',$default=''){
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
	static function entity($name,$id){
		if(!$name){
			$instanceOf = Search::firstInstanceOf($id);
			if($instanceOf){
				$name = '['.$instanceOf.']';
			}else{
				$name = '--?--';
			}
		}
		return '<a href="/entity/read/'.$id.'">'.self::limit($name).'</a>';
	}
	static function entityRelation($name,$id){
		return '<a href="/entity/relation/read/'.$id.'">'.self::limit($name).'</a>';
	}
	static function forFactor($value){
		return '<span class="forFactor">'.$value.'</span>';
	}
	static function controlFactor($value){
		return '<span class="controlFactor">'.$value.'</span>';
	}
	static function voteOn(){
		return '<a class="voteOn" title="vote on">[Vote]</a>';
	}
	static function voteOnComment($id){
		return '<a class="voteOnComment" data-commentId="'.$id.'" title="vote on">[Vote]</a>';
	}
	static function significance($significance,$type,$vote=true){
		return '<span class="significance" data-type="'.$type.'">'.$significance.'</span> '.($vote ? ViewTool::voteOn() : '');
		
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
	
	static function dialogIncludes(){
		/*
		$prefix = '/public/resource/MooDialog-0.8/Source/';
		View::addCss(
				$prefix.'css/MooDialog.css'
			);
		View::addTopJs(
				$prefix.'Overlay.js',
				$prefix.'MooDialog.js',
				$prefix.'MooDialog.Fx.js',
				'dialog.js',
				'vote.js'
			);
			*/
	}
	static function datetimeIncludes(){
		View::addCss(
				'/public/resource/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css',
				'/public/resource/jquery-timepicker/timepicker.css'
			);
		View::addTopJs('/resource/jquery-ui/js/jquery-ui-1.10.3.custom.min.js');
		View::addBottomJs('/public/resource/jquery-timepicker/jquery-ui-timepicker-addon.js');
	}
	static function editorIncludes(){}
}
