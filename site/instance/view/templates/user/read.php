
<?	if($page->id == User::id()){?>
<div><a class="button" href="../update?id=<?=$page->id?>">Update</a></div>
<?	}?>
<div class="section">
	<div class="title">
		Details
	</div>
	<div class="content">		
		<table class="standard">
			<tbody>
				<tr>
					<td>Display Name</td>
					<td><?=$page->actor['display_name']?></td>
				</tr>
				<tr>
					<td>Created</td>
					<td><?=ViewTool::time($page->actor['time_created'])?></td>
				</tr>
<?	if($page->user){?>
				<tr>
					<td>Public Statement</td>
					<td><?=htmlspecialchars($page->user['public_statement'])?></td>
				</tr>
				<tr>
					<td>Website</td>
					<td><?=htmlspecialchars($page->user['website'])?></td>
				</tr>
<?	}?>
			</tbody>
		</table>
	</div>
</div>

<div class="section">
	<div class="title">
		Latest Opinions
	</div>
	<div class="content">
<?	if($page->latestOpinions){?>
<?		foreach($page->latestOpinions as $opinion){?>
	<div class="opinion">
		<div class="opinionTitle">
			<?=$opinion['title']?> for <a href="/person/read/<?=$opinion['_id']?>"><?=$opinion['name_first']?> <?=$opinion['name_middle']?> <?=$opinion['name_last']?></a>
			<?=ViewTool::time($opinion['time_created'])?>
		</div>
		<div class="opinionText">
			<?=$opinion['text']?>
		</div>
	</div>
<?		}?>
<?	}else{?>
				No Opinions
<?	}?>
	</div>
</div>