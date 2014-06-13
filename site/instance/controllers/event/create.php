<?
if($id = i()->CRUDController()->create()->return){
	Page::success();
	Page::saveMessages();
	Http::redirect('read/'.$id);
}
ViewTool::datetimeIncludes();
View::end('@pageCreate');