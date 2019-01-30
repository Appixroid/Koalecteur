<?php 
	function getFormatedDate(){
		$days = array('Mercredi', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');
		$mouths = array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
		$today = getdate();
		$str = $days[$today['wday']] . " " . $today['mday'] . " " . $mouths[$today['mon']] . ", " . $today['year'];
		return $str;
	}
?>