<?
if(!$page->title && $page->title !== false){
	$page->title = View::pageTitle();
}