<?php
session_start();



require_once $_SERVER["DOCUMENT_ROOT"] . "/config.php";

require_once $_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/user.class.php";

require_once $_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/bans.class.php";



$bans = new Bans(true);



if ($_SESSION["user"]["banned"] === true)

{

	$data = $bans->getBan();

	?>

	<!DOCTYPE html>

	<html>

		<head>

			<title>Je bent verbannen</title>

			

			<style>

				*, *::after, *::before {

					box-sizing: border-box;

				}



				#container {

					width: 300px;

					perspective: 600px;

					position: absolute;

					top: 50%;

					left: 50%;

					transform: translate(-50%, -50%);

					font-family: Verdana;

					font-size: 11px;

				}

				

				#logo {

					text-align: center;

					margin-bottom: 15px;

				}

				

				#info {

					background: #FFCCCB;

					border: 3px solid #EA0909;

					padding: 10px;

					-webkit-border-radius: 10px;

					-moz-border-radius: 10px;

					border-radius: 10px;

				}

			</style>

		</head>

		<body>

			<div id="container">

				<div id="logo">

					Logo hier

				</div>

				

				<div id="info">

					<?php

					echo "Je bent verbannen op het paneel van -. Hieronder volgt verdere data betreft je ban. Indien dit volgens jou verkeerd is, neem contact op met iemand van het beheer.<br /><br />";

					echo "<strong>Type ban:</strong> " . $data["type"] . "<br />";

					echo "<strong>Verbannen op:</strong> " . $data["added_on"] . "<br />";

					echo "<strong>Door:</strong> " . $data["by"] . "<br />";

					echo "<strong>Reden:</strong> " . $data["reason"] . "<br /><br />";

					echo "Met vriendelijke groet, <br />Het Beheer";

					?>

				</div>

			</div>

		</body>

	</html>	

	<?php

}

else

{

	header("Location: /");

	exit;

}

?>