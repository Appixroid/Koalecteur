<!DOCTYPE html>
<html>
<head>
    <link href='https://fonts.googleapis.com/css?family=Playfair+Display:400,700,900,400italic,700italic,900italic|Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="templates/styles/newspaper.css">
    <title>Newspaper Style Design Experiment</title>
    <meta name="viewport" content="width=device-width">

</head>
<body>
	<?php
		include('templates/lib/date.php');
	?>
<div class="head">
    <div class="headerobjectswrapper">
        <div class="weatherforcastbox"><span style="font-style: italic;">Meteo du prochain jour: Grand soleil</span><br><span>Vent: 7km/h;<br> Temperature: 10 Degres;<br> Humidite: 82%</span></div>
        <header>Le Koalecteur</header>
    </div>

    <div class="subhead">Montpellier, Occitanie - <?php echo getFormatedDate(); ?> - 10 actualites</div>
</div>
<div class="content">
		
		<?php 
			KoalecteurSystem::includeNews();
		?>

</div>
</body>
</html>
