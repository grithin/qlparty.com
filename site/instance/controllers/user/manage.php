<?
User::requirePrivilege('admin');

if(i()->CRUDController()->delete()->return){
	Page::success('deleted');
	Page::saveMessages();
	Http::redirect('/');
}


$page->tool->readSortPage();
View::end('@pageManage');