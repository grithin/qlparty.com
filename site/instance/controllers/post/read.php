<?
getId();
$page->tool->readCheck();
$page->title = 'Post: '.htmlspecialchars($page->item['title']);
View::end('@pageRead');