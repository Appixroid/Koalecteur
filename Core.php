<?php
	
	function getPath($path)
	{
		return (ADMIN ? "../" : "") . $path;
	}
	
	function getNameFile()
	{
		return getPath("data/name/" . KEY . ".name");
	}
	
	function getSourcesFile()
	{
		return getPath("data/sources/" . KEY . ".csv");
	}

	define("KEY", (isset($_GET['key']) && !empty($_GET['key']) ? $_GET['key'] : "default"));
	define("NAME", explode("\n", file_get_contents(getNameFile()))[0]);
?>
