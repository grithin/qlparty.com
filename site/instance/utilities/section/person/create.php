<?
class PageTool extends Model{
	static function create(){
		Page::$in['actor_id_creater'] = User::actorId();
		return CRUDModel::create();
	}
}
