<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="Het paneel van - in Habbo Hotel!" /> 
		<meta name="author" content="Wessel Verheij" /> 
		<meta name="copyright" content="Wessel Verheij" /> 
		<meta name="robots" content="nosnippet">
		<meta name="googlebot" content="noodp" /> 
		
		<title>Imperial Maffia - Welkom</title>		
		<link rel="stylesheet" href="/_paneel/assets/css/reset.css" />
		<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:300,400,400italic,600,700" type="text/css">
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" />
		<link rel="stylesheet" href="/_paneel/assets/css/login.css">
		
		<script type="text/javascript" src="/_paneel/assets/js/jquery.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$('.button-pass-forgot').click(function() {
					if ($('.button-login').css("opacity") == 0)
					{
						$('.button-login').animate({
							opacity: 1
						}, "slow");
					}
					
					if ($('.login').is(":visible"))
					{
						$('.login').animate({
							height: "toggle",
							'padding-top': 'toggle',
							'padding-bottom': 'toggle',
							opacity: "toggle"
						}, "slow");
					}
					
					if ($('.register').is(":visible"))
					{
						$('.register').animate({
							height: "toggle",
							'padding-top': 'toggle',
							'padding-bottom': 'toggle',
							opacity: "toggle"
						}, "slow");
					}
					
					if ($('.password').is(":hidden"))
					{
						$('.password').animate({
							height: "toggle",
							'padding-top': 'toggle',
							'padding-bottom': 'toggle',
							opacity: "toggle"
						}, "slow");
					}
				});
				
				$('.button-login').click(function() {
					if ($('.button-login').css("opacity") == 1)
					{
						$('.button-login').animate({
							opacity: 0
						}, "slow");
					}
					
					if ($('.login').is(":hidden"))
					{
						$('.login').animate({
							height: "toggle",
							'padding-top': 'toggle',
							'padding-bottom': 'toggle',
							opacity: "toggle"
						}, "slow");
					}
					
					if ($('.register').is(":visible"))
					{
						$('.register').animate({
							height: "toggle",
							'padding-top': 'toggle',
							'padding-bottom': 'toggle',
							opacity: "toggle"
						}, "slow");
					}
					
					if ($('.password').is(":visible"))
					{
						$('.password').animate({
							height: "toggle",
							'padding-top': 'toggle',
							'padding-bottom': 'toggle',
							opacity: "toggle"
						}, "slow");
					}
				});
				
				$('.button-register').click(function() {
					if ($('.button-login').css("opacity") == 0)
					{
						$('.button-login').animate({
							opacity: 1
						}, "slow");
					}
					
					if ($('.login').is(":visible"))
					{
						$('.login').animate({
							height: "toggle",
							'padding-top': 'toggle',
							'padding-bottom': 'toggle',
							opacity: "toggle"
						}, "slow");
					}
					
					if ($('.register').is(":hidden"))
					{
						$('.register').animate({
							height: "toggle",
							'padding-top': 'toggle',
							'padding-bottom': 'toggle',
							opacity: "toggle"
						}, "slow");
					}
					
					if ($('.password').is(":visible"))
					{
						$('.password').animate({
							height: "toggle",
							'padding-top': 'toggle',
							'padding-bottom': 'toggle',
							opacity: "toggle"
						}, "slow");
					}
				});
				
				$("#login-submit").click(function(e) {
					e.preventDefault();
					
					var username = $("#login-username").val();
					var password = $("#login-password").val();
					
					$.post("/_paneel/modules/landing/login.php", { username: username, password: password })
					.done(function(data) {
						if (data == "correct")
						{
							document.location.reload(true);
						}
						else
						{
							$("#login-errors").html(data);
						}   
					});
				});
				
				$("#register-submit").click(function(e) {
					e.preventDefault();
					
					var username = $("#register-username").val();
					var password = $("#register-password").val();
					var passwordRepeat = $("#register-password-repeat").val();
					
					$.post("/_paneel/modules/landing/register.php", { username: username, password: password, password_repeat: passwordRepeat })
					.done(function(data) {
						if (data == "correct")
						{
							$("#register-succesfull").html("Je bent geregistreerd! Je kan nu inloggen.");
							
							setTimeout(function() {
								document.location.reload(true);
							}, 3000);
						}
						else
						{
							var container = $("#register-errors");
							container.html("");
							
							$(data).each(function(index, value) {
								container.append("- " + value + "<br />");
							});
						}
					});
				});
				
				$("#passreset-submit").click(function(e) {
					e.preventDefault();
					
					var username = $("#passreset-username").val();
					var password = $("#passreset-password").val();
					var passwordRepeat = $("#passreset-password-repeat").val();
					
					$.post("/_paneel/modules/landing/passreset.php", { username: username, password: password, password_repeat: passwordRepeat })
					.done(function(data) {
						if (data == "correct")
						{
							$("#passreset-succesfull").html("Je hebt je wachtwoord succesvol veranderd! Je kan nu inloggen met je nieuwe wachtwoord.");
							
							setTimeout(function() {
								document.location.reload(true);
							}, 3000);
						}
						else
						{
							var container = $("#passreset-errors");
							container.html("");
							
							$(data).each(function(index, value) {
								container.append("- " + value + "<br />");
							});
						}
					});
				});
			});
		</script>	
	</head>	
	<body>
		<div class="page-title">
			Logo hier
		</div>
		
		<div class="module form-module">
			<div class="button-login">
				<i class="fa fa-home"></i>
			</div>
			
			<div class="form login">
				<p id="login-errors"></p>
				<h2>Inloggen</h2>
				<p>Welkom op het paneel van -.</p>
				
				<form>
					<input type="text" id="login-username" placeholder="Habbonaam"/>
					<input type="password" id="login-password" placeholder="Wachtwoord"/>
					<button id="login-submit">Inloggen</button>
				</form>
			</div>
			
			<div class="form register">
				<p id="register-errors"></p>
				<p id="register-succesfull"></p>
				<h2>Registreren</h2>
				<p>Voordat je registreert, zet de volgende unieke code in je Habbo missie: <strong><?= $landing->getUniqueKey() ?></strong></p>
				
				<form>
					<input type="text" id="register-username" placeholder="Habbonaam"/>
					<input type="password" id="register-password" placeholder="Wachtwoord"/>
					<input type="password" id="register-password-repeat" placeholder="Wachtwoord (herhaling)"/>
					<button id="register-submit">Registeer</button>
				</form>
			</div>
			
			<div class="form password">
				<p id="passreset-errors"></p>
				<p id="passreset-succesfull"></p>
				<h2>Wachtwoord vergeten</h2>
				<p>Om gebruik te maken van de wachtwoord vergeten functie, moet een Raad van Bestuur lid je hiervoor toestemming geven. Indien je deze hebt, zet dan eerst de volgende unieke code in je Habbo missie voordat je verder gaat: <strong><?= $landing->getUniqueKey() ?></strong></p>				
				<form>
					<input type="text" id="passreset-username" placeholder="Habbonaam"/>
					<input type="password" id="passreset-password" placeholder="Nieuwe wachtwoord"/>
					<input type="password" id="passreset-password-repeat" placeholder="Nieuwe wachtwoord (herhaling)"/>
					<button id="passreset-submit">Verander</button>
				</form>
			</div>
			
			<div class="footer-button button-register"><a href="#">Registreren</a></div>
			<div class="footer-button button-pass-forgot"><a href="#">Wachtwoord vergeten</a></div>
		</div>
	</body>
</html>