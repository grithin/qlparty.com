<? 
/**
Can configure with array: $page->templateOptions, using:
	create = false
	delete = false
	description = text
*/
$fields = $page->tool->gets(array('show','title','format'),$page->concern ? $page->concern : CRUDPage::MANAGE);
?>
<style type="text/css">
	.item{
		border:10px solid #FFDCAE;
		border-radius:10px 10px 10px 10px;
		padding:10px;
		margin-bottom:7px;
	}
	.item .itemType{
		font-size:12pt;
		color:#666;
	}
	.item .title{
		font-size:15pt;
		display:inline-block;
	}
	.item .info{
		float:right;
		padding-left:15px;
		font-size:8pt;
		color:#333;
	}
	.item .body{
		padding-left:10px;
		font-size:12pt;
		padding-top:5px;
	}
	.item .title2{
		font-size:10pt;
		color:#333;
		font-weight:bold;
		padding-top:5px;
	}
	.button{
		font-size:10pt;
	}
	.tab1{
		padding-left:1em;
		display:inline-block;
	}
	.tab5{
		padding-left:5em;
		display:inline-block;
	}
</style>
<a class="button" href="/event/create">Add Event</a>
<a class="button" href="/post/create">Add Post</a>
<br/>
<br/>
<div data-paging="<?=$page->paging['pages'].','.$page->paging['page']?>" data-sort="<?=$page->sort?>">
<?	foreach($page->items as $item){?>
	<div class="item">
<?		if($item['type_id_'] == 1){/*candidate*/?>
		<div class="title">
			<span class="itemType">Potential Candidate:</span> 
			<a href="/candidate/read/<?=$item['foreign_id']?>"><?=$item['row']['name_first'].' '.$item['row']['name_last']?></a>
		</div>
		<div class="info">
			<?=ViewTool::timeAgo($item['row']['time_created'])?> by <?=ViewTool::actor($item['row']['display_name'],$item['row']['actor_id'])?>
		</div>
		<div class="body">
			<div class="title2">Desired Position</div>
			<div>
				<div class="tab1"></div><?=$item['row']['desired_position']?>
			</div>
			<div class="title2">Location</div>
			<div>
				<div class="tab1"></div><?=$item['row']['location']?>
			</div>
			<div class="title2">Qualifications</div>
			<div>
				<div class="tab1"></div><?=$item['row']['qualifications']?>
			</div>
			<div class="title2">Personal Support</div>
			<div>
				<div class="tab1"></div><?=$item['row']['personal_support']?>
			</div>
			<div class="title2">About</div>
			<div>
				<div class="tab1"></div><?=$item['row']['about']?>
			</div>
<?			if($item['row']['notes']){?>
			<div class="title2">Notes</div>
			<div>
				<div class="tab1"></div><?=$item['row']['notes']?>
			</div>
			<?=$item['row']['text']?>
<?			}?>
		</div>
<?		}elseif($item['type_id_'] == 2){/*volunteer*/?>
		<div class="title">
			<span class="itemType">Volunteer:</span> 
			<a href="/volunteer/read/<?=$item['foreign_id']?>"><?=$item['row']['name_first'].' '.$item['row']['name_last']?></a>
		</div>
		<div class="info">
			<?=ViewTool::timeAgo($item['row']['time_created'])?> by <?=ViewTool::actor($item['row']['display_name'],$item['row']['actor_id'])?>
		</div>
		<div class="body">
			<div class="title2">Skills</div>
			<div>
				<div class="tab1"></div><?=$item['row']['skills']?>
			</div>
			<div class="title2">Time Available</div>
			<div>
				<div class="tab1"></div><?=$item['row']['time_available']?>
			</div>
			<div class="title2">Location</div>
			<div>
				<div class="tab1"></div><?=$item['row']['location']?>
			</div>
<?			if($item['row']['notes']){?>
			<div class="title2">Notes</div>
			<div>
				<div class="tab1"></div><?=$item['row']['notes']?>
			</div>
			<?=$item['row']['text']?>
<?			}?>
		</div>
<?		}elseif($item['type_id_'] == 3){/*post*/?>
		<div class="title">
			<span class="itemType">Post:</span> 
			<a href="/post/read/<?=$item['foreign_id']?>"><?=$item['row']['title']?></a>
		</div>
		<div class="info">
			<?=ViewTool::timeAgo($item['row']['time_created'])?> by <?=ViewTool::actor($item['row']['display_name'],$item['row']['actor_id'])?>
		</div>
		<div class="body"><?=$item['row']['text']?></div>
<?		}elseif($item['type_id_'] == 4){/*event*/?>
		<div class="title">
			<span class="itemType">Event:</span> 
			<a href="/event/read/<?=$item['foreign_id']?>"><?=$item['row']['title']?></a>
		</div>
		<div class="info">
			<?=ViewTool::timeAgo($item['row']['time_created'])?> by <?=ViewTool::actor($item['row']['display_name'],$item['row']['actor_id'])?>
		</div>
		<div class="body">
			<?=$item['row']['text']?>
			<div class="title2">Location</div>
			<div>
				<div class="tab1"></div><?=$item['row']['location']?>
			</div>
			<div class="title2">Times</div>
			<div>
				<div class="tab1"></div>From <?=ViewTool::humanDatetime($item['row']['time_start'],$item['row']['time_zone'])?> to <?=ViewTool::humanDatetime($item['row']['time_end'],$item['row']['time_zone'])?>
			</div>
			
		</div>
<?		}elseif($item['type_id_'] == 5){/*newsletter*/?>
<?		}?>
	</div>
	<div class="paging"></div>
<?	}?>
</div>