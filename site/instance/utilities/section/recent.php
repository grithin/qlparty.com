<?
class pageTool extends Person{
	static function get(){
		//+	opinions {
		//10 most recent
		Page::$data->recent = Db::rows('select po.*, a.display_name, p.name_first, p.name_middle, p.name_last, p.id person_id
			from person_opinion po
				left join actor a on po.actor_id_creater = a.id
				left join person p on po._id = p.id
			order by po.time_created desc
			limit 10');
		//daily most voted on
		Page::$data->daily = Db::rows('select po.*, a.display_name, p.name_first, p.name_middle, p.name_last, p.id person_id
			from person_opinion po
				left join actor a on po.actor_id_creater = a.id
				left join person p on po._id = p.id
			where po.time_created >= '.Db::quote(i()->Time('-24 hours')).'
			order by po.vote_up + po.vote_down desc, po.time_created desc
			limit 10');
		//weekly most voted on
		Page::$data->weekly = Db::rows('select po.*, a.display_name, p.name_first, p.name_middle, p.name_last, p.id person_id
			from person_opinion po
				left join actor a on po.actor_id_creater = a.id
				left join person p on po._id = p.id
			where po.time_created >= '.Db::quote(i()->Time('-7 days')).'
			order by po.vote_up + po.vote_down desc, po.time_created desc
			limit 10');
		//monthly most helpful
		Page::$data->monthly = Db::rows('select po.*, a.display_name, p.name_first, p.name_middle, p.name_last, p.id person_id
			from person_opinion po
				left join actor a on po.actor_id_creater = a.id
				left join person p on po._id = p.id
			where po.time_created >= '.Db::quote(i()->Time('-1 month')).'
			order by po.vote_up - po.vote_down desc, po.time_created desc
			limit 10');
		//+	}
	}
}
