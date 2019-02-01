<?php
	define("ADMIN", true);
	include("../Core.php");
	include(Core::buildPath("KoalecteurSystem.php"));

	if(isset($_POST['login']) && isset($_POST['pwd']))
	{
		$user = Core::connect($_POST['login'], $_POST['pwd']);
		if($user == null)
		{
			header("Location: login.html");
		}
		else
		{
			$_SESSION['user'] = $user[0];
			header("Location: index.php?key=" . $user[1]);
		}
	}
	else if(!isset($_SESSION['user']))
	{
		header("Location: login.html");
	}
	else if(isset($_GET['disconnect']))
	{
		session_destroy();
		header("Location: index.php?key=" . KEY);
	}
	else if(isset($_POST['name']))
	{
		Core::setName($_POST['name']);
		header("Location: index.php?key=" . KEY);
	}
	else if(isset($_POST['remove']))
	{
		Core::removeSource($_POST['remove']);
		header("Location: index.php?key=" . KEY);
	}
	else if(isset($_POST['add']) && !empty($_POST['add']))
	{
		Core::addSource($_POST['add']);
		header("Location: index.php?key=" . KEY);
	}

	$sources = Core::getSources();
?>

<!DOCTYPE html>
<html>
	<head>
		<title><?php echo NAME;?> - Administrator</title>

		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
 		<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
		<link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.brown-light_green.min.css" />

		<link rel="stylesheet" href="style/style.css" type="text/css">

		<script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
	</head>

	<body>
		<!-- Always shows a header, even in smaller screens. -->
		<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
		  <header class="mdl-layout__header">
			<div class="mdl-layout__header-row">

			  <!-- Title -->
			  <span class="mdl-layout-title"><?php echo NAME;?></span>

			  <!-- Add spacer, to align navigation to the right -->
			  <div class="mdl-layout-spacer"></div>

			  <!-- Navigation. We hide it in small screens. -->
			  <nav class="mdl-navigation mdl-layout--large-screen-only">
				<a class="mdl-navigation__link" target="_blank" href="../index.php?key=<?php echo KEY;?>">Go to the Koalecteur</a>
				<a class="mdl-navigation__link" href="index.php?disconnect=true&key=<?php echo KEY;?>">Log Out</a>
			  </nav>
			</div>
		  </header>

		  <div class="mdl-layout__drawer">
			<span class="mdl-layout-title"><?php echo NAME;?></span>

			<nav class="mdl-navigation">
				<a class="mdl-navigation__link" target="_blank" href="../index.php?key=<?php echo KEY;?>">Go to the Koalecteur</a>
				<a class="mdl-navigation__link" href="index.php?disconnect=true&key=<?php echo KEY;?>">Log Out</a>
				<a class="mdl-navigation__link" href="koalecteur.news">Help</a>
			</nav>
		  </div>

		  <main class="mdl-layout__content">
			<div class="page-content">
				<form action="index.php?modify=name&key=<?php echo KEY;?>" method="POST">

					<h3 class="centered">Koalecteur Name :</h3>
					<div class="mdl-textfield mdl-js-textfield fullwidth-field">
						<input class="mdl-textfield__input" type="text" id="name" name="name" value="<?php echo NAME?>" required />
						<label class="mdl-textfield__label" for="name">Koalecteur Flux Name...</label>
					</div>

					<input class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent centered-button" type="submit" value="Modify"/>
				</form>

				<h3 class="centered">Sources :</h3>
				<form action="index.php?key=<?php echo KEY;?>" method="POST">
					<input type="hidden" name="add" value="" id="new-source" />
					<input class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent centered-button" onclick="return askNewSource();" type="submit" value="Add Source" />
				</form>
				<?php
				foreach($sources as $source)
				{
					$key = KEY;
					echo <<< END
					<div class="list-action mdl-list">
						<div class="mdl-list__item">
							<span class="mdl-list__item-primary-content">
						  		<i class="material-icons mdl-list__item-avatar">rss_feed</i>
						  		<span>$source</span>
							</span>
							<form method="POST" action="index.php?key=$key">
								<input type="hidden" name="remove" value="$source"/>
								<input class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent centered-button" type="submit" value="Delete"/>
							</form>
					  	</div>
					</div>
END;
				}

				?>
			</div>
		  </main>
		</div>

		<script>
			function askNewSource()
			{
				var source = prompt("Enter the RSS url : ");
				if(source != "" && source != null)
				{
					document.getElementById("new-source").value = source;
					return true;
				}

				return false;
			}
		</script>
	</body>
</html>
