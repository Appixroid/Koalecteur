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
		
		public static function setName($newName)
		{
			$prep = self::$pdo->prepare("UPDATE Koalecteurs SET name = :name WHERE hashkey = :hk");
			$prep->execute(array("name" => $newName, "hk" => KEY));
		}
		
		public static function getSources()
		{
			$prep = self::$pdo->prepare("SELECT url FROM Agregate WHERE hashkey = :hk");
			$prep->execute(array("hk" => KEY));
			$sourcesAssoc = $prep->fetchAll(PDO::FETCH_ASSOC);
			$sources = array();
			
			foreach($sourcesAssoc as $item)
			{
				array_push($sources, $item['url']);
			}
			
			return $sources;
		}
		
		public static function addSource($source)
		{
			$prep = self::$pdo->prepare("INSERT INTO Agregate (hashkey, url) VALUES (:hk, :source)");
			$prep->execute(array("hk" => KEY, "source" => $source));
		}
		
		public static function removeSource($source)
		{
			$prep = self::$pdo->prepare("DELETE FROM Agregate WHERE hashkey = :hk AND url = :source");
			$prep->execute(array("hk" => KEY, "source" => $source));
		}
		
		public static function buildPath($path)
		{
			return (ADMIN ? "../" : "") . $path;
		}
	}
	
	Core::connectPdo();

	define("NAME", Core::getName());
?>
