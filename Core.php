<?php
	session_start();
	require_once("Log.php");

	define("KEY", (isset($_GET['key']) && !empty($_GET['key']) ? $_GET['key'] : "default"));

	class Core
	{
		private static $pdo;

		public static function connectPdo()
		{
			self::$pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PWD);
		}

		public static function getPdo()
		{
			return self::$pdo;
		}

		public static function getView()
		{
			$prep = self::$pdo->prepare("SELECT view FROM Koalecteurs WHERE hashkey = :hk");
			$prep->execute(array("hk" => KEY));
			return $prep->fetch()[0];
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

		public static function connect($login, $pwd)
		{
			$prep = self::$pdo->prepare("SELECT u.password, a.hashkey FROM Users u JOIN Administrate a ON a.login = u.login WHERE u.login = :login");
			$prep->execute(array("login" => $login));
			$assoc = $prep->fetch(PDO::FETCH_ASSOC);

			if($assoc == false)
			{
				return null;
			}
			else
			{
				if(password_verify($pwd, $assoc["password"]))
				{
					return array(array("login" => $login, "pwd" => $assoc["password"]), $assoc["hashkey"]);
				}
				else
				{
					return null;
				}
			}
		}

		public static function buildPath($path)
		{
			return __DIR__ . "/" . $path;
		}
	}

	Core::connectPdo();
?>
