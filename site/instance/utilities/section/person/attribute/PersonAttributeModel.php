<?
class PersonAttributeModel{
	static $model = array('table'=>'person_attribute');
	static $id;
	static function validate(){
		Page::filterAndValidate(array(
			'type_id' => '!p:isAttribute',
			'value' => 'f:trim,v:lengthRange|1;250',
		));
	}
	static function getAttributeTypes(){
		Page::$data->attributeTypes = Db::columnKey('id','select id, name from person_attribute_type order by name');
		foreach(Page::$data->attributeTypes as &$type){
			$type = ucwords($type);
		}unset($type);
	}
	static function read(){
		if(Page::$data->item = Db::row(PageTool::$model['table'],PageTool::$id)){
			return true;
		}
		badId();
	}
	static function isAttribute($value){
		if(!Db::check('person_attribute_type',$value)){
			throw new InputException('Must have at least one part of the name');
		}
	}
	static function authorized(){
		if(!User::inGroup('admin')){
			if(!Db::check('person_opinion',array('id'=>PageTool::$id,'actor_id_creater'=>User::actorId()))){
				die('not authorized');
			}
		}
	}
}
