<?
class pageTool extends Person{
	static function validate(){
		Page::filterAndValidate(array(
				'name_first' => FieldIn::prepend('?!v:filled',FieldIn::$fieldTypes['name']),
				'name_middle' => FieldIn::prepend('?!v:filled',FieldIn::$fieldTypes['name']),
				'name_last' => FieldIn::prepend('?!v:filled',FieldIn::$fieldTypes['name']),
			));
		return !Page::errors();
	}
	static function search(){
		//break up name if specifics not given
		if(!Page::$in['name_first'] && !Page::$in['name_middle'] && !Page::$in['name_last']){
			if($parts = Person::getNameParts(Page::$in['name'])){
				Arrays::mergeInto(Page::$in,$parts);
			}else{
				return false;
			}
		}
		if(self::validate()){
			Page::$data->results = Search::getAttributes(Search::basic(Page::$in['name_first'],Page::$in['name_middle'],Page::$in['name_last']));
			return Page::$data->results;
		}
	}
}
