<?
///simple class to help with outputting form tags with post and pre filled data
/** All values are escaped*/
class Form {
	///used as object when methods called statically
	static $genericForm;
	///total number of fields generated.  Used when no name provided
	public $fieldCount = 0;
	function __construct($data=null,$options=null){
		///an array of keys to values used in conjuction with TO_ARRAY to serve as override values for form fields
		if(!is_array($data)){
			$this->data = Page::$data->item;
		}else{
			$this->data = $data;
		}
		//to add as a prefix to form elements' classes
		$this->defaultClassPrefix = $options['defaultClassPrefix'] ? $options['defaultClassPrefix'] : Config::$x['defaultFormFieldClassPrefix'];
		///the the of behavior for determining what the actual value of a form field should be
		$this->valueBehavior = $options['valueBehavior'] ? $options['valueBehavior'] : 'to_input';
		///attribute parsers to apply when handling options on form method
		$this->additionalParsers = $options['additionalParsers'];
	}
	
	/// used to translate static calls to the primary database instance
	static function __callStatic($name,$arguments){
		if(!self::$genericForm){
			$class = __class__;
			self::$genericForm = new $class;
		}
		//allow _func to be treated as func
		return call_user_func_array(array(self::$genericForm,$name),$arguments);
	}
	/// prefix function with "_" to get temporary value override behavior, and make first argument the overriding behavior.
	function __call($name,$arguments){
		//allow _func to be treated as func
		if(!method_exists($this,$name)){
			$name = substr($name,1);
			//allow _func to be treated as func
			if(!method_exists(__class__,$name)){
				Debug::throwError('Bad Form class method');
			}
			$formerValueBehavior = $this->valueBehavior;
			$this->valueBehavior = array_shift($arguments);
			$return = call_user_func_array(array($this,$name),$arguments);
			$this->valueBehavior = $formerValueBehavior;
			return $return;
		}
		return call_user_func_array(array($this,$name),$arguments);
	}
	
	
	///tries to find value using page input.
	/**  If not found: 
			if useArray = true, uses $this->data
			if useArray = false, uses param if not null, otherwise uses $this->data
	*/
	function to_input($name, $value=null, $useArray=false){
		if (isset(Page::$in[$name])){
			return Page::$in[$name];
		}else{
			$matches = Http::getSpecialSyntaxKeys($name);
			if($matches && Arrays::isElement($matches,Page::$in)){
				return Arrays::getElementReference($matches,Page::$in);
			}else{
				if($useArray){
					return $this->data[$name];
				}else{
					if($value === null  && isset($this->data[$name])){
						return $this->data[$name];
					}else{
						return $this->to_param($name,$value);
					}
				}
			}
		}
	}
	///leaves value of $value to be $value (ie, does nothing)
	function to_param($name,$value=null){
		return $value;
	}
	///tries to find value in array $this->data.  If not found, tries using page input
	function to_array($name,$value=null){
		if(is_array($this->data) && isset($this->data[$name])){
			return $this->data[$name];
		}else{
			return $this->to_input($name,$value);
		}
	}
	///tries to user input.  If not found, tries using array $this->data
	function to_params($name,$value=null){
		return $this->to_input($name,$value,true);
	}
	
	/// resolves the value for a given form field name
	function resolveValue($name, $value=null, $behavior=null,$allowArrays=false){
		$behavior = $behavior ? $behavior : $this->valueBehavior;
		if(!is_array($behavior)){
			$behavior = array($this,$behavior);
		}
		$value = call_user_func_array($behavior,array($name,$value));
		
		if(!$allowArrays){
			FieldIn::makeString($value);
		}
		
		//PageTool integration with Form for field form output (use with FieldOut to format form fields)
		if(isset(PageTool::$fieldOut) && PageTool::$fieldOut[$name]){
			call_user_func_array(PageTool::$fieldOut[$name],array(&$value));
		}
		
		return $value;
	}
	
	///used internally.  Generates the additional attributes provides for a field
	function additionalAttributes($x,$name='default'){
		if($this->additionalParsers){
			foreach($this->additionalParsers as $parser){
				list($x,$name) = call_user_func($parser,$x,$name);
			}
		}
		$classes = Arrays::remove(explode(' ',$x['class']));
		if($this->defaultClassPrefix){
			$defaultClass = $this->defaultClassPrefix.($name ? $name : $this->fieldCount);
			array_unshift($classes,$defaultClass);
		}
		unset($x['class']);
		if($classes){
			$additions[] = 'class="'.implode(' ',$classes).'"';
		}
		$this->fieldCount++;
		if($x['extra']){
			$additions[] = $x['extra'];
		}
		unset($x['extra']);
		
		$attributeTypes = array('id','title','alt','rows','placeholder');
		foreach($attributeTypes as $attribute){
			if($x[$attribute]){
				$additions[] = $attribute.'="'.$x[$attribute].'"';
				unset($x[$attribute]);
			}
		}
		if($x){
			//add onclick events
			foreach($x as $k=>$v){		
				if(strtolower(substr($k,0,2)) == 'on'){
					$additions[] = $k.'="'.$v.'"';
				}elseif(strtolower(substr($k,0,5) == 'data-')){
					$additions[] = $k.'="'.htmlspecialchars($v).'"';
				}elseif($k[0] == '@'){
					if($v !== null){
						$additions[] = substr($k,1).'="'.htmlspecialchars($v).'"';
					}else{
						$additions[] = substr($k,1);
					}
				}
			}
		}
		
		if($additions){
			return ' '.implode(' ',$additions).' ';
		}
	}


	/// create a <select> tag
	/**
	@param	name attribute of tag
	@param	value attribute of tag
	@param	options	array array(value=>text,value=>text) key to value where key is the option value and value is the option text.  I did it this way because in the backend the values are usually the keys;
	@param	x	list of options including "id", "class", "on[click|mouseover|...]", "alt", "title" and "extra", which is included in the tag
	@note	this is a simplified version which doesn't have option groups or freeform options.  I might introduce the more complicated version later
	*/
	private function select($name, $options, $value = null, $x = null){
		$values = $this->resolveValue($name, $value,null,true);
		if(!is_array($values)){
			$values = array($values);
		}
		//makes an array where values are turned into an array of keys (= value) where each element is true
		$values = array_fill_keys($values, true);
		
		$specialX = Arrays::separate(array('none','noneValue'),$x);
		
		//create an array specifying the selected options
		$detailedOptions = array();
		//allow for empty array
		if($options){
			foreach($options as $k=>$v){
				if(is_array($v)){
					$detailedOptions[$k] = $v;
				}else{
					$detailedOptions[$k] = array('display'=>$v);
				}
				if($values[$k]){
					$detailedOptions[$k]['selected'] = true;
				}
			}
		}
		
		$field =  '<select name="'.$name.'" '.$this->additionalAttributes($x,$name).'>';
		if($specialX['none']){
			$value = 0;
			if (isset($specialX['noneValue'])){
				$value = $specialX['noneValue'];
			}
			$field .= '<option value="'.$value.'">'.$specialX['none'].'</option>';
		}
		if($detailedOptions){
			foreach ($detailedOptions as $k=>$details){
				if($x['capitalize']){
					$details['display'] = ucwords($details['display']);
				}
				$field .= '<option '.
					($details['selected']?'selected=1':null)
					.' value="'.htmlspecialchars($k).'" '.
					($details['x'] ? $this->additionalAttributes($details['x']) : '').
					'>'.htmlspecialchars($details['display']).'</option>';
				
			}
		}
		return $field.'</select>';
	}

	/// create a <input type="radio"> element
	/** checked if "checked" = true or if resolvedValue = value
	@param	name attribute of tag
	@param	value attribute of tag
	@param	checked indicates whether field is checked or not
	@param	x	list of options including "id", "class", "on[click|mouseover|...]", "alt", "title" and "extra", which is included in the tag
	*/
	private function radio($name, $option, $checked=null, $x=null){
		$checked = $checked ? $option : $checked;
		$value = $this->resolveValue($name,$checked);//ie, if checked, pass in name of option as value, otherwise, pass in the blank value to serve as referenced variable
		return '<input type="radio" name="'.$name.'" '.($value == $option ?' checked':null).$this->additionalAttributes($x,$name).' value="'.htmlspecialchars($option).'" />';
	}
	/// create an <input type="checkbox"> element
	/** checked if "checked" = true or if resolvedValue = value
	@param	name attribute of tag
	@param	checked indicates whether field is checked or not
	@param	x	list of options including "id", "class", "on[click|mouseover|...]", "alt", "title" and "extra", which is included in the tag
	@note	the only value is 1, because checkboxes should probably be unique in the name and values other than 1 are unnecessary
	*/
	private function checkbox($name, $checked=null, $x=null, $value=null){
		$checkedValue = $this->resolveValue($name,$checked);
		$value = $value ? $value : 1;
		$on = $this->hasValue($checkedValue) && $checkedValue != '0';
		return '<input type="checkbox" name="'.$name.'" '.($on?' checked="1" ':null).$this->additionalAttributes($x,$name).' value="'.htmlspecialchars($value).'" />';
	}

	/// create an <input type="text"> element
	/** checked if "checked" = true or if resolvedValue = value
	@param	name attribute of tag
	@param	value attribute of tag
	@param	x	list of options including "id", "class", "on[click|mouseover|...]", "alt", "title" and "extra", which is included in the tag
	*/
	private function text($name, $value=null, $x=null){
		$value = $this->resolveValue($name,$value);
		return '<input type="text" name="'.$name.'" '.($this->hasValue($value)?' value="'.htmlspecialchars($value).'" ':null).$this->additionalAttributes($x,$name).'/>';
	}

	/// create an <input type="file"> element
	/** checked if "checked" = true or if resolvedValue = value
	@param	name attribute of tag
	@param	value attribute of tag
	@param	x	list of options including "id", "class", "on[click|mouseover|...]", "alt", "title" and "extra", which is included in the tag
	*/
	private function file($name, $value=null, $x=null){
		$value = $this->resolveValue($name,$value);
		return '<input type="file" name="'.$name.'" '.($this->hasValue($value)?' value="'.htmlspecialchars($value).'" ':null).$this->additionalAttributes($x,$name).'/>';
	}

	/// create a <textarea> element
	/** checked if "checked" = true or if resolvedValue = value
	@param	name attribute of tag
	@param	value attribute of tag
	@param	x	list of options including "id", "class", "on[click|mouseover|...]", "alt", "title" and "extra", which is included in the tag
	*/
	private function textarea($name, $value=null, $x=null){
		$value = $this->resolveValue($name,$value);
		return '<textarea name="'.$name.'" '.$this->additionalAttributes($x,$name).'>'.($this->hasValue($value)?htmlspecialchars($value):null).'</textarea>';
	}

	/// create an <input type="password"> element
	/** checked if "checked" = true or if resolvedValue = value
	@param	name attribute of tag
	@param	value attribute of tag
	@param	x	list of options including "id", "class", "on[click|mouseover|...]", "alt", "title" and "extra", which is included in the tag
	*/
	private function password($name, $value=null, $x=null){
		$value = $this->resolveValue($name,$value);
		return '<input type="password" name="'.$name.'"'.($this->hasValue($value)?'value="'.htmlspecialchars($value).'" ':null).$this->additionalAttributes($x,$name).'/>';
	}

	/// create an <input type="hidden"> element
	/** checked if "checked" = true or if resolvedValue = value
	@param	name attribute of tag
	@param	value attribute of tag
	@param	x	list of options including "id", "class", "on[click|mouseover|...]", "alt", "title" and "extra", which is included in the tag
	*/
	private function hidden($name,$value=null,$x=null){
		$value = $this->resolveValue($name,$value);
		return '<input type="hidden" name="'.$name.'" '.($this->hasValue($value)?'value="'.htmlspecialchars($value).'" ':null).$this->additionalAttributes($x,$name).'/>';
	}

	/// create an <input type="submit"> element
	/** checked if "checked" = true or if resolvedValue = value
	@param	name attribute of tag
	@param	value attribute of tag
	@param	x	list of options including "id", "class", "on[click|mouseover|...]", "alt", "title" and "extra", which is included in the tag
	@note	value and name are switchedin param position because it is more likely the value will be desired input than the name.
	*/
	private function submit($value=null,$name=null,$x=null){
		if(!$name){
			$name = $value;
		}
		return '<input type="submit" name="'.$name.'" '.($this->hasValue($value)?'value="'.htmlspecialchars($value).'" ':null).$this->additionalAttributes($x,$name).'/>';
	}
	///used to determine if a value actually exists
	function hasValue($value){
		if($value || $value === '0' || $value === 0){
			return true;
		}
	}
}