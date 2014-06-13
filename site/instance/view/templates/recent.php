<?	if($page->recent){?>
<div class="section" data-voteUrl="/person/opinion/vote">
	<div class="title">
		Recent
	</div>
	<div class="content">
<?		foreach($page->recent as $opinion){?>
		<div class="opinion" data-id="<?=$opinion['id']?>">
			<div>
				<div class="voteOnIt">
					<div class="voteUp"><?=$opinion['vote_up']?></div>
					<div class="voteDown"><?=$opinion['vote_down']?></div>
				</div>
			</div>
			<div class="opinionTitle"><?=$opinion['title']?> 
				by <a href="/user/read/<?=$opinion['actor_id_creater']?>"><?=$opinion['display_name']?></a> 
				for <a href="/person/read/<?=$opinion['_id']?>"><?=$opinion['name_first']?> <?=$opinion['name_middle']?> <?=$opinion['name_last']?></a>
				<?=ViewTool::time($opinion['time_created'])?>
			</div>
			<div class="opinionText">
				<?=$opinion['text']?>
			</div>
		</div>
<?		}?>
	</div>
</div>
<?	}?>

<?	if($page->daily){?>
<div class="section" data-voteUrl="/person/opinion/vote">
	<div class="title">
		Daily Most Active
	</div>
	<div class="content">
<?		foreach($page->daily as $opinion){?>
		<div class="opinion" data-id="<?=$opinion['id']?>">
			<div>
				<div class="voteOnIt">
					<div class="voteUp"><?=$opinion['vote_up']?></div>
					<div class="voteDown"><?=$opinion['vote_down']?></div>
				</div>
			</div>
			<div class="opinionTitle"><?=$opinion['title']?> 
				by <a href="/user/read/<?=$opinion['actor_id_creater']?>"><?=$opinion['display_name']?></a> 
				for <a href="/person/read/<?=$opinion['_id']?>"><?=$opinion['name_first']?> <?=$opinion['name_middle']?> <?=$opinion['name_last']?></a>
				<?=ViewTool::time($opinion['time_created'])?>
			</div>
			<div class="opinionText">
				<?=$opinion['text']?>
			</div>
		</div>
<?		}?>
	</div>
</div>
<?	}?>


<?	if($page->weekly){?>
<div class="section" data-voteUrl="/person/opinion/vote">
	<div class="title">
		Weekly Most Active
	</div>
	<div class="content">
<?		foreach($page->weekly as $opinion){?>
		<div class="opinion" data-id="<?=$opinion['id']?>">
			<div>
				<div class="voteOnIt">
					<div class="voteUp"><?=$opinion['vote_up']?></div>
					<div class="voteDown"><?=$opinion['vote_down']?></div>
				</div>
			</div>
			<div class="opinionTitle"><?=$opinion['title']?> 
				by <a href="/user/read/<?=$opinion['actor_id_creater']?>"><?=$opinion['display_name']?></a> 
				for <a href="/person/read/<?=$opinion['_id']?>"><?=$opinion['name_first']?> <?=$opinion['name_middle']?> <?=$opinion['name_last']?></a>
				<?=ViewTool::time($opinion['time_created'])?>
			</div>
			<div class="opinionText">
				<?=$opinion['text']?>
			</div>
		</div>
<?		}?>
	</div>
</div>
<?	}?>


<?	if($page->monthly){?>
<div class="section" data-voteUrl="/person/opinion/vote">
	<div class="title">
		Monthly Most Helpful
	</div>
	<div class="content">
<?		foreach($page->monthly as $opinion){?>
		<div class="opinion" data-id="<?=$opinion['id']?>">
			<div>
				<div class="voteOnIt">
					<div class="voteUp"><?=$opinion['vote_up']?></div>
					<div class="voteDown"><?=$opinion['vote_down']?></div>
				</div>
			</div>
			<div class="opinionTitle"><?=$opinion['title']?> 
				by <a href="/user/read/<?=$opinion['actor_id_creater']?>"><?=$opinion['display_name']?></a> 
				for <a href="/person/read/<?=$opinion['_id']?>"><?=$opinion['name_first']?> <?=$opinion['name_middle']?> <?=$opinion['name_last']?></a>
				<?=ViewTool::time($opinion['time_created'])?>
			</div>
			<div class="opinionText">
				<?=$opinion['text']?>
			</div>
		</div>
<?		}?>
	</div>
</div>
<?	}?>
