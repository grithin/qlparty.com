<?
class PersonModel{
	static $model = array('table'=>'person');
	static $fieldOut = array('time_for'=>array('FieldOut','datetime'));
	static $id;
	static function validate(){
		Page::filterAndValidate(array(
			'' => 'p:namePresent',
			'name_first' => FieldIn::prepend('?!v:filled',FieldIn::$fieldTypes['name']),
			'name_middle' => FieldIn::prepend('?!v:filled',FieldIn::$fieldTypes['name']),
			'name_last' => FieldIn::prepend('?!v:filled',FieldIn::$fieldTypes['name']),
		));
	}
	static function namePresent(){
		if(!Page::$in['name_first'] && !Page::$in['name_middle'] && !Page::$in['name_last']){
			 throw new InputException('Must have at least one part of the name');
		 }
	}
	static function read(){
		if(Page::$data->item = Db::row(PageTool::$model['table'].' t left join actor a on t.actor_id_creater = a.id',array('t.id'=>PageTool::$id),'t.*, a.display_name')){
			Page::$data->item['attributes'] = Db::rows('select pa.*, pat.name 
				from person_attribute pa
					left join person_attribute_type pat on pat.id = pa.type_id
				where pa._id = '.PageTool::$id.'
				order by pa.vote_up desc, pa.time_created desc');
			Page::$data->item['opinions'] = Db::rows('select po.*, a.display_name
					from person_opinion po
						left join actor a on po.actor_id_creater = a.id
					where po._id = '.self::$id.'
					order by po.vote_up desc, po.time_created desc
				');
			return true;
		}
		badId();
	}
}
