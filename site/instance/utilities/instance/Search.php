<?
class Search{
	static function basic($first,$middle,$last){
		if($first){
			$where[] = 'p.name_first = '.Db::quote($first);
		}
		if($middle){
			$where[] = 'p.name_middle = '.Db::quote($middle);
		}
		if($last){
			$where[] = 'p.name_last = '.Db::quote($last);
		}
		return Db::rows('select p.*, a.display_name 
			from person p 
				left join actor a on p.actor_id_creater = a.id
			where '.implode(' AND ',$where).'
			order by time_created asc
			limit 100');
	}
	static function getAttributes($results){
		foreach($results as &$result){
			$result['attributes'] = Db::rows('select pa.value, pa.type_id, pat.name 
				from person_attribute pa
					left join person_attribute_type pat on pat.id = pa.type_id
				where pa._id = '.$result['id']);
		}unset($result);
		return $results;
	}
}
