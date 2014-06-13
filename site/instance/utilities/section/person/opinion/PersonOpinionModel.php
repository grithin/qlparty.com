<?
class PersonOpinionModel{
	static $model = array('table'=>'person_opinion');
	static $id;
	static function validate(){
		Page::filterAndValidate(array(
			'title' => FieldIn::prepend('?!v:filled',FieldIn::$fieldTypes['title']),
			'text' => Person::$validators['opinion'],
		));
	}
	static function getActionTypes(){
		Page::$data->types = Db::columnKey('id','occurrence_type',null,'id,name');
	}
	static function read(){
		if(Page::$data->item = Db::row(PageTool::$model['table'],PageTool::$id)){
			return true;
		}
		badId();
	}
	static function delete(){
		if($deleted = CRUDModel::delete()){
			Db::delete('occurrence_occurrence',array('_id_relatee'=>self::$id));
			Db::delete('occurrence_goal',array('_id'=>self::$id));
			return $deleted;
		}
	}
	static function pointsCalculate(){
		Page::$in['points'] = Points::evaluate(Page::$in['amount'],Page::$in['intensity'],Page::$in['type_id']);
	}
	static function authorized(){
		if(!User::inGroup('admin')){
			if(!Db::check('person_opinion',array('id'=>PageTool::$id,'actor_id_creater'=>User::actorId()))){
				die('not authorized');
			}
		}
	}
}
