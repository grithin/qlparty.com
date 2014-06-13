<?
class PageTool extends Model{
	static function read($personId){
		Page::$data->person = Db::row('person',$personId);
		if(!Page::$data->person){
			badId();
		}
	}
	static function create(){
		Page::$in['actor_id_creater'] = User::actorId();
		Page::$in['_id'] = Page::$in['person'];
		return CRUDModel::create();
	}
}
