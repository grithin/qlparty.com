<?
class PageTool extends CRUDPage{
	public $model = array(
			'table'=>'activity'
		);
	public $crud = array();
	function readSortPage(){
		parent::readSortPage(null,null,10);
		$this->types = Db::columnKey('id','select * from activity_type');
		foreach($this->items as &$item){
			$row = Db::row($this->types[$item['type_id_']]['table'],$item['foreign_id']);
			$row['display_name'] = Db::row('actor',$row['actor_id'],'display_name');
			foreach($row as &$part){
				$part = self::shortenHtml($part,500,'...',-3);
			}
			$item['row'] = $row;
		}
	}
	static function shortenHtml($value,$max,$ending=null,$maxOnMax=null){
		$value = html_entity_decode(strip_tags($value));
		$value = self::shorten($value,$max,$ending,$maxOnMax);
		return htmlspecialchars($value);
	}
	static function shorten($value,$max,$ending=null,$maxOnMax=null){
		if(strlen($value) > $max){
			$value = substr($value,0,$max);
			if($maxOnMax !== null){
				$value = substr($value,0,$maxOnMax);
			}
			$value .= $ending;
		}
		return $value;
	}
}
