<style type="text/css">
	.searchInput{
		/* tool http://css3generator.com/ */
		-webkit-border-radius: 0px 50px 50px 50px;
		border-radius: 0px 50px 50px 50px; 
		border:10px solid #ffeeee;
		width: 350px;
	}
	
	#searchResults{
		margin-top:20px;
	}
	.searchResult{
		-webkit-border-radius: 0px 50px 50px 50px;
		border-radius: 0px 50px 50px 50px; 
		border:10px solid #ffeeee;
		padding:10px;
	}
	.creationDetail{
		margin-left:10px;
		font-size:small;
	}
	.personAttributes{
		margin-left:20px;
	}
	#selectPerson{
		padding:30px;
		padding-bottom:10px;
	}
	.selectThisPerson{
		margin-left:30px;
	}
</style>

<form method="get" action="">
	<?=Form::text('name_first','',array('class'=>'searchInput','placeholder'=>'First Name'))?>
	<br/>
	<?=Form::text('name_middle','',array('class'=>'searchInput','placeholder'=>'Middle Name'))?>
	<br/>
	<?=Form::text('name_last','',array('class'=>'searchInput','placeholder'=>'Last Name'))?>
	
	<button name="search" value="1" class="specialButton">Search</button>
</form>
<?	if(Page::$in['select']){?>
<div id="selectPerson">
	<b>Are you adding an opinion about any of the following people?</b>
	<br/><br/><a href="/person/create?_cmd_create=1&createOpinion=1&name_first=<?=Page::$in['name_first']?>&name_middle=<?=Page::$in['name_middle']?>&name_last=<?=Page::$in['name_last']?>">Nope; no one in the list</a>
</div>
<?	}?>

<?	if($page->results){?>
<div id="searchResults">
<?		foreach($page->results as $result){?>
	<div class="searchResult">
		<a href="/person/read/<?=$result['id']?>"><?=$result['name_first']?> <?=$result['name_middle']?> <?=$result['name_last']?> </a>
<?			if(Page::$in['select']){?>
			<span class="selectThisPerson"><a href="/person/opinion/create?person=<?=$result['id']?>&saved=1&_cmd_create=1">Yes, this person</a></span>
<?			}?>
		
		
		<br/>
		<span class="creationDetail">
			<?=ViewTool::time($result['time_created'])?>
			by <a href="/user/read/<?=$result['actor_id_creater']?>"><?=$result['display_name']?></a>
		</span>
<?			if($result['attributes']){?>
		<div class="personAttributes">
<?				foreach($result['attributes'] as $attribute){?>
			<div class="attribute">
				<span class="_name"><?=$attribute['name']?></span> : <span class="_value"><?=htmlspecialchars($attribute['value'])?></span>
			</div>
<?				}?>
		</div>
<?			}?>
	</div>
<?		}?>
</div>
<?	}?>