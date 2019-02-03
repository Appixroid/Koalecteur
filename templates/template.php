<!DOCTYPE html>
<html>
<head>
    <link href='https://fonts.googleapis.com/css?family=Playfair+Display:400,700,900,400italic,700italic,900italic|Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="templates/styles/newspaper.css">
    <title><?php echo $_KOALECTEUR['name']; ?></title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <meta name="viewport" content="width=device-width">

</head>
<body>
	<?php
		include('templates/lib/date.php');
	?>
<div class="head">
    <div class="headerobjectswrapper">
        <div class="weatherforcastbox"><img src="https://www.meteofrance.com/integration/sim-portail/generated/integration/img/vigilance/mn.gif"></div>
        <header><?php echo $_KOALECTEUR['name']; ?></header>
    </div>

    <div class="subhead"><?php echo getFormatedDate(); ?> - <div id="nbArticle" style="display: inline;"></div> actualites</div>
</div>
<div id="options">
<div id="form"><?php KoalecteurSystem::includeSettings(); ?></div>
<i id="optionButton" class="material-icons">keyboard_arrow_down</i>
</div>
<div class="content">

		<?php
			KoalecteurSystem::includeNews();
		?>

</div>
<script>
document.getElementById("nbArticle").innerText = "<?php echo KoalecteurSystem::$newsCount; ?>";



</script>
</body>
</html>
