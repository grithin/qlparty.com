<?
class PageTool extends Model{
	function read($id){
		if($id){
			$this->item = Db::row('actor',$id,'display_name,id');
		}
	}
}