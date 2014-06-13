<?
class Activity{
	static function add($typeId,$foreignId){
		if($foreignId){
			Db::insert('activity',array(
					'type_id_' => $typeId,
					'time_created' => i()->Time(),
					'foreign_id' => $foreignId
				));
		}
	}
	static function delete($typeId,$foreignIds){
		$foreignIds = (array)$foreignIds;
		//clear from activity
		foreach($foreignIds as $id){
			Db::delete('activity',array('type_id_'=>$typeId,'foreign_id'=>$id));
		}
		return $foreignIds;
	}
}
