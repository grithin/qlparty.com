<?
class PageTool extends Model{
	static function read(){
		$item = Db::row(self::$model['table'],self::$id);
		if(!$item){
			badId();
		}
	}
	static function create(){
		Page::$in['_id'] = self::$id;;
		
		Page::filterAndValidate(array(
			'_id' => '!v:filled,p:idExists',
			'direction' => '!v:filled,p:confirmDirection',
		));
		
		if(!Page::errors()){
			$insert = Arrays::extract(array('_id','direction'),Page::$in);
			$insert['actor_id_creater'] = User::actorId();
			
			$existing = Db::row('select * from '.PageTool::$model['table'].'_vote 
				where actor_id_creater = '.Db::quote($insert['actor_id_creater']).'
					and _id = '.$insert['_id']);
			if($existing){
				if($insert['direction'] != $existing['direction']){
					Db::update(PageTool::$model['table'].'_vote',array('direction'=>$insert['direction']),$existing['id']);
				}
			}else{
				$vote['id'] = Db::insert(PageTool::$model['table'].'_vote',$insert);
			}
			
			$vote['up'] = abs(Db::row('select sum(direction) from '.PageTool::$model['table'].'_vote where direction = 1 and _id = '.$insert['_id']));
			$vote['down'] = abs(Db::row('select sum(direction) from '.PageTool::$model['table'].'_vote where direction = -1 and _id = '.$insert['_id']));
			Db::update(PageTool::$model['table'],array(
					'vote_up' => $vote['up'],
					'vote_down' => $vote['down'],
				),$insert['_id']);
			
			
			return $vote;
		}else{
			return Page::errors();
		}
	}
	static function idExists($value){
		if(!Db::check(self::$model['table'],$value)){
			throw new InputException('Id not found');
		}
	}
	static function confirmDirection($value){
		if($value != 1 && $value != -1){
			throw new InputException('Your vote was misdirected');
		}
	}
}
