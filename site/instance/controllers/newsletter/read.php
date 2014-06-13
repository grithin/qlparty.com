<?
getId();
$page->tool->readCheck();
$page->title = 'Newsletter: '.htmlspecialchars($page->item['title']);
View::end('@pageRead');