<?php
	define("ADMIN", true);
	include("../Core.php");
	include(getPath("KoalecteurSystem.php"));
	
	if(isset($_GET['modify']))
	{
		switch($_GET['modify'])
		{
			case "name":
				if(isset($_POST['name']))
				{
					file_put_contents(getNameFile(), $_POST['name']);
				}
				break;
				
			case "sources":
				if(isset($_POST['sources']))
				{
					file_put_contents(getSourcesFile(), str_replace("\n", ';', $_POST['name']));
				}
				break;
		}
		
		header("Location: index.php?key=" . KEY);
	}
	
	$sources = str_replace(";", "\n", file_get_contents(getSourcesFile()));
?>

<!DOCTYPE html>
<html>
	<head>
		<title><?php echo NAME;?></title>
		
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
				<a class="mdl-navigation__link" href="../index.php?key=<?php echo KEY;?>">Go to the Koalecteur</a>
			  </nav>
			</div>
		  </header>
		  
		  <div class="mdl-layout__drawer">
			<span class="mdl-layout-title"><?php echo NAME;?></span>
			
			<nav class="mdl-navigation">
				<a class="mdl-navigation__link" href="../index.php?key=<?php echo KEY;?>">Go to the Koalecteur</a>
				<a class="mdl-navigation__link" href="koalecteur.news">Help</a>
			</nav>
		  </div>
		  
		  <main class="mdl-layout__content">
			<div class="page-content">
				<form action="index.php?modify=name&key=<?php echo KEY;?>" method="POST">
					
					<h3>Koalecteur Name :</h3> 
					<div class="mdl-textfield mdl-js-textfield fullwidth-field">
						<input class="mdl-textfield__input" type="text" id="name" name="name" value="<?php echo NAME?>" required />
						<label class="mdl-textfield__label" for="name">Koalecteur Flux Name...</label>
					</div>
					
					<input class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent centered-button" type="submit" value="Modify"/>
				</form>
				
				<form action="index.php?modify=sources&key=<?php echo KEY;?>" method="POST">
					
					<h3>Sources :</h3> 
					<div class="mdl-textfield mdl-js-textfield fullwidth-field">
						<textarea class="mdl-textfield__input" type="text" rows="10" name="sources" id="sources" ><?php echo $sources;?></textarea>
						<label class="mdl-textfield__label" for="sources">Sources...</label>
					  </div>
					<input class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent centered-button" type="submit" value="Modify"/>
				</form>
			</div>
		  </main>
		</div>
	</body>
</html>
