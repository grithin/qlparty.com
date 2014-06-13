<!DOCTYPE html>
<html xml:lang="en" lang="en">
	<head>
		<title><?=$page->headTitle?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="description" content="<?=$page->description?>"/>
		<meta name="keywords" content="<?=$page->keywords?>"/>
		<meta name="viewport" content="width=960">
		<link rel="icon" type="image/png" href="/public/img/icon.png">
		<?=View::getCss(array('modDate'=>$page->resourceModDate))?>
		<!-- Thoughtpush js object variable used by tp js tools-->
		<script type="text/javascript">var tp = {};</script>
		<?=View::getJson()?>
		<?=View::getTopJs(array('modDate'=>$page->resourceModDate))?>
	</head>
	<body id="<?=implode('-',RequestHandler::$urlTokens)?>-body" class="<?=$page->section?> pure-form">
		<div id="test">
		
		</div>
		
		<div id="header">
			<div id="headerLogo">
				<a href="/">
					<img id="headerImg" src="/public/img/logo2.png" />
					
				</a>
			</div>		
			<div id="topLinks" class="navbar">
				<div class="bar">
					<a href="/concept">About</a>
					<a href="/activity">Activity</a>
					<a href="/aims">Aims</a>
					<a href="/volunteer/create">Volunteer</a>
					<a href="/candidate/create">Become a Candidate</a>
					<a href="/messaging/manage">Messaging <span id="messageCount"></span></a>
					<a href="/donate">Donate</a>


<?	if($_SESSION['userId']){?>
					<a href="/user/">User</a>
					<a href="/user/logout">Log Out</a>
<?	}else{?>
					<a href="/user/login">Login</a>
					<a href="/user/signup">Sign Up</a>
<?	}?>
				</div>
			</div>
		</div>
		
		<div id="body">
			<div id="content">
				<div id="defaultMsgBox" class="msgBox"></div>
				<?=$input?>
			</div>
		</div>
		<div id="footer">
			<div>
				&copy; Quality of Life Party | <a href="/concept">About</a> | <a href="/dev/null">Feedback</a> | <a href="/user/tou">Terms of Use</a>
			</div>
		</div>
		<?=View::getBottomJs(array('modDate'=>$page->resourceModDate))?>
	</body>
</html>
