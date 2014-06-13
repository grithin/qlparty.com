<?
class InputValidator{
	static $errorMessages = array(
//+	basic validators{
			'exists' => 'Missing field {_FIELD_}',
			'filled' => 'Missing field {_FIELD_}',
			'existsInTable' => 'No record of {_FIELD_} found',
			'isInteger' => '{_FIELD_} must be an integer',
			'isFloat' => '{_FIELD_} must be a decimal',
			'matchRegex' => '{_FIELD_} must match %s',
			'existsAsKey' => '{_FIELD_} did not contain an accepted value',
			'existsAsValue' => '{_FIELD_} did not contain an accepted value',
			'isEmail' => '{_FIELD_} must be a valid email',
			'isEmailLine' => '{_FIELD_} did not match the format "NAME &lt;EMAIL&gt;',
			'isUrl' => '{_FIELD_} must be a URL',
			'intInRange.max' => '{_FIELD_} must be %s or less',
			'intInRange.min' => '{_FIELD_} must be %s or more',
			'length' => '{_FIELD_} must be a of a length equal to %s',
			'lengthRange.max' => '{_FIELD_} must have a length of %s or less',
			'lengthRange.min' => '{_FIELD_} must have a length of %s or more',
			'date' => '{_FIELD_} must be a date.  Most date fromats are accepted',
			'timezone' => '{_FIELD_} must be a timezone',
			'noTagIntegrity' => '{_FIELD_} is lacking HTML Tag context integrity.  That might pass on congress.gov, but not here.',
			'matchValue' => '{_FIELD_} does not match expected value',
			'isMime' => '{_FIELD_} must have one of the following mimes: %s',
			'isNotMime' => '{_FIELD_} must not have any of the following mimes: %s',
//+	}
//+	More specialized validators{			
			'phone.area' => 'Please include an area code in {_FIELD_}',
			'phone.check' => 'Please check {_FIELD_}',
			'zip' => '{_FIELD_} was malformed',
			'age.max' => '{_FIELD_} too old.  Must be at most %s',
			'age.min' => '{_FIELD_} too recent.  Must be at least %s',
//+	}
		);
	///true or false return instead of exception
	/**
	@param	method	method  to call
	@param	args...	anything after method param is passed to method
	*/
	static function check(){
		$args = func_get_args();
		$method = array_shift($args);
		try{
			call_user_func_array(array('self',$method),$args);
			return true;
		}catch(InputException $e){
			return false;
		}
	}
//+	basic validators{
	static function exists(&$value){
		if(!isset($value)){
			throw new InputException(self::$errorMessages['exists']);
		}
	}
	static function filled(&$value){
		if(!isset($value) || $value === ''){
			throw new InputException(self::$errorMessages['filled']);
		}
	}
	static function existsInTable(&$value,$table,$field='id'){
		if(!Db::check($table,array($field=>$value))){
			throw new InputException(self::$errorMessages['existsInTable']);
		}
	}
	static function isInteger(&$value){
		if(!Tool::isInt($value)){
			throw new InputException(self::$errorMessages['isInteger']);
		}
	}
	static function isFloat(&$value){
		if(filter_var($value, FILTER_VALIDATE_FLOAT) === false){
			throw new InputException(self::$errorMessages['isFloat']);
		}
	}
	static function matchValue(&$value,$match){
		if($value !== $match){
			throw new InputException(self::$errorMessages['matchValue']);
		}
	}
	static function matchRegex(&$value,$regex,$matchModel=null){
		if(!preg_match($regex,$value)){
			if(!$matchModel){
				$matchModel = Tool::regexExpand($regex);
			}
			throw new InputException(sprintf(self::$errorMessages['matchRegex'],'"'.$matchModel.'"'));
		}
	}
	static function existsAsKey(&$value,$array){
		if(!isset($array[$value])){
			throw new InputException(self::$errorMessages['existsAsKey']);
		}
	}
	static function existsAsValue(&$value){
		$args = func_get_args();
		array_shift($args);
		if(is_array($args[0])){
			$array = $args[0];
		}else{
			$array = $args;
		}
		if(!in_array($value,$array)){
			throw new InputException(self::$errorMessages['existsAsValue']);
		}
	}
	static function isEmail($value){
		if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
			throw new InputException(self::$errorMessages['isEmail']);
		}
	}
	//potentially including name: joe johnson <joe@bob.com>
	static function isEmailLine($value){
		if(!self::check('isEmail',$value)){
			preg_match('@<([^>]+)>@',$value,$match);
			$email = $match[1];
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
				throw new InputException(self::$errorMessages['isEmail']);
			}
			if(!preg_match('@^[a-z0-9 _\-.]+<([^>]+)>$@i',$value)){
				throw new InputException(self::$errorMessages['isEmailLine']);
			}
		}
	}
	static function isUrl($value){
		if(!filter_var($value, FILTER_VALIDATE_URL)){
			throw new InputException(self::$errorMessages['isUrl']);
		}
	}
	static function intInRange($value,$min=null,$max=null){
		if(Tool::isInt($max) && $value > $max){
			throw new InputException(sprintf(self::$errorMessages['inRange.max'],$max));
		}
		if(Tool::isInt($min) && $value < $min){
			throw new InputException(sprintf(self::$errorMessages['inRange.min'],$min));
		}
	}
	static function length($value,$length){
		$actualLength = strlen($value);
		if($actualLength != $length){
			throw new InputException(sprintf(self::$errorMessages['length'],$length));
		}
	}
	static function lengthRange($value,$min=null,$max=null){
		$actualLength = strlen($value);
		if(Tool::isInt($max) && $actualLength > $max){
			throw new InputException(sprintf(self::$errorMessages['lengthRange.max'],$max));
		}
		if(Tool::isInt($min) && $actualLength < $min){
			throw new InputException(sprintf(self::$errorMessages['lengthRange.min'],$min));
		}
	}
	static function timezone($value){
		try {
			new DateTimeZone($value);
		} catch(Exception $e) {
			throw new InputException(self::$errorMessages['timezone']);
		}
	}
	
	static function date($value){
		try{
			i()->Time($value);
		}catch(Exception $e){
			throw new InputException(self::$errorMessages['date']);
		}
	}
	/**
	@param	mimes	array of either whole mimes "part/part", or the last part of the mime "part"
	*/
	static function isMime($v,$name,$mimes){
		$mimes = Arrays::stringArray($mimes);
		$mime = File::mime($_FILES[$name]['tmp_name']);
		foreach($mimes as $matchMime){
			if(preg_match('@'.preg_quote($matchMime).'$@',$mime)){
				return true;
			}
		}
		$mimes = implode(', ',$mimes);
		throw new InputException(sprintf(self::$errorMessages['isMime'],$mimes));
	}
	/**
	@param	mimes	array of either whole mimes "part/part", or the last part of the mime "part"
	*/
	static function isNotMime($v,$name,$mimes){
		$mimes = Arrays::stringArray($mimes);
		$mime = File::mime($_FILES[$name]['tmp_name']);
		foreach($mimes as $matchMime){
			if(preg_match('@'.preg_quote($matchMime).'$@',$mime)){
				$mimes = implode(', ',$mimes);
				throw new InputException(sprintf(self::$errorMessages['isNotMime'],$mimes));
			}
		}
		return true;
	}
//+	}
//+	specialized validators{
	static function zip($value){
		if (!preg_match("/^([0-9]{5})(-[0-9]{4})?$/i",$value)) {
			throw new InputException(self::$errorMessages['zip']);
		}
	}
	static function phone(&$value){
		if(strlen($value) == 11 && substr($value,0,1) == 1){
			$value = substr($value,1);
		}
		if(strlen($value) == 7){
			throw new InputException(self::$errorMessages['phone.area']);
		}
		
		if(strlen($value) != 10){
			throw new InputException(self::$errorMessages['phone.check']);
		}
	}
	
	static function age($value,$min=null,$max=null){
		$time = i()->Time($value);
		$age = $time->diff(i()->Time('now'));
		if(Tool::isInt($max) && $age->y > $max){
			throw new InputException(sprintf(self::$errorMessages['age.max'],$max));
		}
		if(Tool::isInt($min) && $age->y < $min){
			throw new InputException(sprintf(self::$errorMessages['age.min'],$min));
		}
	}
	static function htmlTagContextIntegrity($value){
		preg_replace_callback('@(</?)([^>]+)(>|$)@',array(self,'htmlTagContextIntegrityCallback'),$value);
		//tag hierarchy not empty, something wasn't closed
		if(self::$tagHierarchy){
			throw new InputException(self::$errorMessages['noTagIntegrity']);
		}
	}
	static $tagHierarchy = array();
	static function htmlTagContextIntegrityCallback($match){
		preg_match('@^[a-z]+@i',$match[2],$tagMatch);
		$tagName = $tagMatch[0];
		
		if($match[1] == '<'){
			//don't count self contained tags
			if(substr($match[2],-1) != '/'){
				self::$tagHierarchy[] = $tagName;
			}
		}else{
			$lastTagName = array_pop(self::$tagHierarchy);
			if($tagName != $lastTagName){
				throw new InputException(self::$errorMessages['noTagIntegrity']);
			}
		}
	}
}
//+	}