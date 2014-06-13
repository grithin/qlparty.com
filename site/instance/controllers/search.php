<?
$page->title = 'Search';

if(Page::$in['search']){
	$results = PageTool::search();
	if(!$results){
		Http::redirect('/person/create?_cmd_create=1&createOpinion=1&name_first='.Page::$in['name_first'].'&name_middle='.Page::$in['name_middle'].'&name_last='.Page::$in['name_last']);
	}
}

View::end('@fullCurrent');