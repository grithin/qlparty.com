<?
$page->title = 'Email Verify';

if(PageTool::verify()){
	Page::saveMessages();
	Http::redirect('/user/');
}
View::show('standardPage');
