<?
User::required();

$page->title = 'Feedback';

if(i()->CRUDController()->create()->return){
	Page::success('Feedback sent');
}
View::end('@fullCurrent');
