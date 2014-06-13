<?
class PageTool{
	function verify(){
		$this->item = Db::row('newsletter_subscriber',$this->id);
		Debug::out($this->item,md5($this->item['time_created']),Page::$in['code']);
		if(md5($this->item['time_created']) == Page::$in['code']){
			Db::update('newsletter_subscriber',array('status'=>-1),$this->id);
			return true;
		}
	}
}