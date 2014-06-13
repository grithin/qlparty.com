<?
class SortPage{
	/**
	The use of the sort and page functions can vary in how the result is to interact with the rest of the 
		logic and the UI, so the result present provides as much information as might be necessary
	*/
	static function sort($allowed,$sort=null,$default=null,$quoteColumns=true){
		if(!$sort){
			$sort = Page::$in['_sort'];
		}
		$sorts = explode(',',$sort);
		foreach($sorts as $sort){
			$order = substr($sort,0,1);
			if($order == '-'){
				$order = ' DESC';
				$field = substr($sort,1);
			}else{
				if($order == '+'){
					$field = substr($sort,1);
				}else{
					$field = $sort;
				}
				$order = ' ASC';
			}
			if(in_array($field,$allowed)){
				if($quoteColumns){
					$field = Db::quoteIdentity($field);
				}
				$orders[]  = $field.$order;
				$usedSorts[] = $sort;
			}
		}
		if(!$orders){
			if($default){
				return self::sort($allowed,$default,null,$quoteColumns);
			}
		}
		return array(
				'sql' => ' ORDER BY '.implode(', ',$orders).' ',
				'orders' => $orders,
				'sort' => implode(',',$usedSorts)
			);
	}
	static function page($sql,$pageBy=50,$max=null){
		if(Page::$in['_page']){
			$page = abs((int)Page::$in['_page'] - 1);
		}else{
			$page = 0;
		}
		$offset = $pageBy * $page;
		$sql .= "\nLIMIT ".$offset.', '.$pageBy;
		
		list($count,$rows) = Db::countAndRows(($max ? $max + 1 : null),$sql);
		$top = $count;
		if($max && $count > $max){
			$top = $max;
		}
		$pages = ceil($top/$pageBy);
		return array(
				'rows' => $rows,
				'info' => array(
					'count' => $count,
					'pages' => $pages,
					'top' => $top,
					'page' => ($page + 1)
				)
			);
	}
}
