<!DOCTYPE html>
<html>
<head>
    <title>Community Paneel</title> 
	
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="Content-Language" content="nl" />
	<meta name="description" content="Het paneel van - in Habbo Hotel!" /> 
	<meta name="author" content="Wessel Verheij" /> 
	<meta name="copyright" content="Wessel Verheij" /> 
	<meta name="robots" content="nosnippet">
	<meta name="googlebot" content="noodp" /> 

	<link rel="stylesheet" href="/_paneel/assets/css/style.css" type="text/css" />
	<link rel="stylesheet" href="/_paneel/assets/css/style-dev.css" type="text/css" />
	<link rel="stylesheet" href="/_paneel/assets/plugins/spoilers/spoilers.css" type="text/css" />
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" type="text/css">
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:300,400,400italic,600,700" type="text/css">
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Montserrat:400,700" type="text/css" />
	
	<script type="text/javascript" src="/_paneel/assets/js/jquery.js"></script>
	<script type="text/javascript" src="/_paneel/assets/plugins/ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="/_paneel/assets/plugins/spoilers/spoilers.js"></script>
	<script type="text/javascript" src="/_paneel/assets/js/blockadblock.js"></script>
	<script type="text/javascript" src="/_paneel/assets/js/main.js"></script>
</head>

<?php
$contentWidth = $_GET['p'] == 'forum' || $_GET['p'] == 'conversaties' || $_GET['p'] == 'profiel' ? "1000px" : "650px";
?>

<body leftmargin="0px" topmargin="0px" rightmargin="0px" bottommargin="0px" marginwidth="0px" marginheight="0px">
	<nav class="content-left">
		<div class="menu-header">
			<div class="content">
				<img src="#" class="badge-header" title="-" alt="-" />
			</div>
		</div>
		<?php include_once $_SERVER["DOCUMENT_ROOT"] . "/_paneel/include/menu.php"; ?>
	</nav>
	
	<div class="content-right">
		<div class="header">
			<div class="header-left">
				<div class="v-center">
					<a href="https://github.com/lessevv/communitypanel">Community Panel</a>
				</div>
			</div>
			
			<div class="header-right text-bait">
				<div class="header-button link-button" data-link="/conversaties">
					<div class="v-center">
						<span class="header-alert"><?= $conversaties->checkUnreadConversations($_SESSION['ID']) ?></span>
						<i class="fa fa-comments"></i>
					</div>
				</div>
				<div class="header-user">
					Welkom, <strong class="link-button" data-link="/profiel/<?= $_SESSION['habbonaam'] ?>"><?= $_SESSION['habbonaam'] ?></strong>
				</div>
			</div>
		</div>
		<div class="wrapper">
			<?php
			if (isset($_GET['p']))
			{
				if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/_paneel/page/".$_GET["p"].".php"))
				{
					include($_SERVER["DOCUMENT_ROOT"] . "/_paneel/page/".$_GET["p"].".php");
				}
				else
				{
					include($_SERVER["DOCUMENT_ROOT"] . "/_paneel/page/dashboard.php");
				}
			}
			else
			{
				include($_SERVER["DOCUMENT_ROOT"] . "/_paneel/page/dashboard.php");
			}
			?>
			
			<div class="panel">
				<?php include_once $_SERVER["DOCUMENT_ROOT"] . "/_paneel/modules/ads/menu.html"; ?>
			</div>
		</div>
	</div>
	
	<script type="text/javascript">
		$("#toggle-dropdown").click(function() {
			$("#user-dropdown").toggle();
		});

		$(".Editor, .message-textarea").each(function() {
			CKEDITOR.replace($(this).attr('id'));
		});
	</script>
</body>
</html>
