<style type="text/css">
	#person{
		padding:20px;
		font-size:20pt;
		text-shadow: 0px 2px 2px #944;
	}
	.attribute{
		padding:3px;
		padding-left:10px;
		height:18px;
		font-size:small;
	}
	.button{
		font-size:8pt;
	}
</style>

<div id="person">
	<?=$page->item['name_first']?> <?=$page->item['name_middle']?> <?=$page->item['name_last']?>
</div>


<div class="section" id="attributes" data-voteUrl="/person/attribute/vote">
	<div class="title">
		Attributes
	</div>
	<div class="content">
		<a class="button" href="/person/attribute/create?person=<?=$page->id?>">Add Attribute</a>
		<br/>
<?	if($page->item['attributes']){?>
<?		foreach($page->item['attributes'] as $attribute){?>
		<div class="attribute" data-id="<?=$attribute['id']?>">
			<span class="_name"><?=$attribute['name']?></span> : <span class="_value"><?=htmlspecialchars($attribute['value'])?></span>
			<div class="voteOnIt">
				<div class="voteUp"><?=$attribute['vote_up']?></div>
				<div class="voteDown"><?=$attribute['vote_down']?></div>
			</div>
		</div>
<?		}?>
<?	}?>
	</div>
</div>

<div class="section" id="opinions" data-voteUrl="/person/opinion/vote">
	<div class="title">
		Opinions
	</div>
	<div class="content">
		<a class="button" href="/person/opinion/create?person=<?=$page->id?>">Add Opinion</a>
		<br/>
<?	if($page->item['opinions']){?>
<?		foreach($page->item['opinions'] as $opinion){?>
		<div class="opinion" data-id="<?=$opinion['id']?>">
			<div>
				<div class="voteOnIt">
					<div class="voteUp"><?=$opinion['vote_up']?></div>
					<div class="voteDown"><?=$opinion['vote_down']?></div>
				</div>
			</div>
			<div class="opinionTitle"><?=$opinion['title']?> by <a href="/user/read/<?=$opinion['actor_id_creater']?>"><?=$opinion['display_name']?></a> <?=ViewTool::time($opinion['time_created'])?></div>
			<div class="opinionText">
				<?=$opinion['text']?>
			</div>
		</div>
<?		}?>
	</div>
<?	}?>
</div>
