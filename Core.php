<?php

	define("KEY", (isset($_GET['key']) && !empty($_GET['key']) ? $_GET['key'] : "default"));
	
	class Core
	{
		private static $pdo;
		
		public static function connectPdo()
		{
			self::$pdo = new PDO("mysql:host=webinfo;dbname=parentl", "parentl", "56789");
		}
		
		public static function getPdo()
		{
			return self::$pdo;
		}
		
		public static function getName()
		{
			$prep = self::$pdo->prepare("SELECT name FROM Koalecteurs WHERE hashkey = :hk");
			$prep->execute(array("hk" => KEY));
			return $prep->fetch()[0];
		}
		
		public static function getSources()
		{
			$prep = self::$pdo->prepare("SELECT s.url FROM Agregate a JOIN Sources s ON a.id = s.id WHERE a.hashkey = :hk");
			$prep->execute(array("hk" => KEY));
			$sourcesAssoc = $prep->fetchAll(PDO::FETCH_ASSOC);
			$sources = array();
			
			foreach($sourcesAssoc as $item)
			{
				array_push($sources, $item['url']);
			}
			
			return $sources;
		}
		
		public static function buildPath($path)
		{
			return (ADMIN ? "../" : "") . $path;
		}
	}
	
	Core::connectPdo();

	define("NAME", Core::getName());
?>
