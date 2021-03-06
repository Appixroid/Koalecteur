<?php
	require_once(Core::buildPath("RSS.php"));

	$_KOALECTEUR = array("key" => KEY, "name" => Core::getName(), "view" => Core::getView());

	class KoalecteurSystem
	{
		public static $sources;// Array of URL sources
		public static $rss_array;// Array of RSS Object
		public static $news_array;// Array of formated news array
		public static $newsCount;// Amount of total news

		/**
		 * Init the static Koalecteur System
		 */
		public static function initSystem()
		{
			self::$sources = Core::getSources();

			self::$rss_array = array();
			foreach(self::$sources as $source)
			{
				array_push(self::$rss_array, new RSS($source));
			}

			self::$news_array = array();
			self::$newsCount = 0;
		}

		/**
		 * Filter a news item with the GET parameters
		 */
		private static function hasToBePush($item)
		{
			if(isset($_GET['q']) && !empty($_GET['q']))// Key Word filter
			{
				$inTitle = strpos($item['title'], $_GET['q']) !== false;
				$inDesc = strpos($item['desc'], $_GET['q']) !== false;

				if(!$inTitle && !$inDesc)
				{
					return false;
				}
			}

			if(isset($_GET['t']) && !empty($_GET['t']))// Date Filter
			{
				if(isset($item['date']))
				{
					$date = new DateTime($item['date']);
					$target = new DateTime($_GET['t']);

					if($date->format("z Y") != $target->format("z Y"))
					{
						return false;
					}
				}
				else
				{
					return false;
				}
			}

			return true;
		}

		/**
		 * Add the news in good order
		 */
		private static function pushAfterDate($item)
		{
			if(self::hasToBePush($item))
			{
				$i = 0;
				$placed = false;

				if(isset($item['date']))
				{
					$date = new DateTime($item['date']);

					while($i < count(self::$news_array) && !$placed)
					{
						if(isset(self::$news_array[$i]['date']))
						{
							$cmpDate = new DateTime(self::$news_array[$i]['date']);
							$diff = $date->diff($cmpDate);
							if($diff->invert == 1)
							{
								array_splice(self::$news_array, $i, 0, array($item));
								self::$newsCount++;
								$placed = true;
							}
							else
							{
								$i++;
							}
						}
						else
						{
							array_splice(self::$news_array, $i, 0, array($item));
							self::$newsCount++;
							$placed = true;
						}
					}
				}

				if(!$placed)
				{
					array_push(self::$news_array, $item);
					self::$newsCount++;
				}
			}
		}

		/**
		 * Generate all the news with the news template
		 */
		public static function includeNews()
		{
			for($j = 0; $j < count(self::$rss_array); $j++)
			{
				$rss = self::$rss_array[$j];
				$isset = isset($_GET['s']) && !empty($_GET['s']);

				if(($isset && parse_url($rss->getRssSourceLink(), PHP_URL_HOST) == $_GET['s']) || (!$isset))
				{
					$count = $rss->getNewsCount();

					for($i = 0; $i < $count; $i++)
					{
						self::pushAfterDate(array("id" => ($rss->getNewsUniqueId($i) != NULL ? $rss->getNewsUniqueId($i) : $i),
					   		         "title" => $rss->getNewsTitle($i),
									 "date" => $rss->getNewsPublicationDate($i),
									 "desc" => $rss->getNewsDescription($i),
									 "link" => $rss->getNewsLink($i),
									 "author" => $rss->getNewsAuthor($i),
									 "category" => $rss->getNewsCategory($i),
									 "comment" => $rss->getNewsComment($i),
									 "enclosure" => $rss->getNewsEnclosure($i)));
					}
				}
			}

			for($i = 0; $i < count(self::$news_array); $i++)
			{
				$_NEWS = self::$news_array[$i];
				include(Core::buildPath("templates/news.php"));
			}

			self::incrementView();
		}

		/**
		 * Generate and include the settings form
		 */
		public static function includeSettings()
		{
			$word = (isset($_GET['q']) ? $_GET['q'] : "");

			$date = "";
			if(isset($_GET['t']) && !empty($_GET['t']))
			{
				$date = (new DateTime($_GET['t']))->format("Y-m-d");
			}

			$form = '<form action="index.php" method="GET">
						<input type="hidden" name="key" value="' . KEY . '" />
						<input type="text" placeholder="' . Core::getTranslation("search") . '" value="' . $word . '" name="q"/>
						<input type="date" value="' . $date . '" name="t"/>
						<select name="s">
							<option value="" selected>' . Core::getTranslation("all_sources") . '</option>';

			foreach(self::$sources as $source)
			{
				$selected = "";
				$parsedSource = parse_url($source, PHP_URL_HOST);

				if(isset($_GET['s']) && !empty($_GET['s']) && $parsedSource == $_GET['s'])
				{
					$selected = "selected";
				}

				$form .= '<option value="' . $parsedSource . '" ' . $selected . '>' . $parsedSource . '</option>';
			}

			$form .= '	</select>
						<input type="submit" value="' . Core::getTranslation("filter") . '" />
					</form>';

			echo $form;
		}

		/*
		 * Increment the view counter
		 */
		public static function incrementView()
		{
			$prep = Core::getPdo()->prepare("UPDATE Koalecteurs SET view = view + 1 WHERE hashkey = :hk");
			$prep->execute(array("hk" => KEY));
		}

		/*
		 * Add the curent koalecteur to favorites
		 */
		public static function addToFavorite()
		{
			$favorites = array();

			if(isset($_COOKIE['favorites']))
			{
				$favorites = unserialize($_COOKIE['favorites']);
			}

			$isIn = false;
			$i = 0;

			while($i < count($favorites) && !$isIn)
			{
				$isIn = $favorites[$i]['hashkey'] == KEY;

				$i++;
			}

			if(!$isIn)
			{
				array_push($favorites, array("hashkey" => KEY, "name" => Core::getName()));
			}

			setCookie("favorites", serialize($favorites), time() + 2678400, "/", "koalecteur.news");
		}

		/*
		 * Remove the curent koalecteur from favorites
		 */
		public static function removeFromFavorite()
		{
			$favorites = array();

			if(isset($_COOKIE['favorites']))
			{
				$favorites = unserialize($_COOKIE['favorites']);
			}

			$isIn = false;
			$i = 0;

			while($i < count($favorites) && !$isIn)
			{
				if($favorites[$i]['hashkey'] == KEY)
				{
					array_splice($favorites, $i, 1);
					$isIn = true;
				}

				$i++;
			}

			setCookie("favorites", serialize($favorites), time() + 2678400, "/", "koalecteur.news");
		}

		/*
		 * Retrieve true if this Koalecteur is already in favorite
		 */
		public static function isInFavorite()
		{
			$favorites = array();

			if(isset($_COOKIE['favorites']))
			{
				$favorites = unserialize($_COOKIE['favorites']);
			}

			$isIn = false;
			$i = 0;

			while($i < count($favorites) && !$isIn)
			{
				$isIn = $favorites[$i]['hashkey'] == KEY;
				$i++;
			}

			return $isIn;
		}

		/*
		 * Include the favorite link markup for that Koalecteur
		 */
		public static function includeFavoriteLink()
		{
			$isFavorite = self::isInFavorite();
			$url = "https://reader.koalecteur.news?key=" . KEY . "&q=" . (isset($_GET['q']) ? urlencode($_GET['q']) : "") . "&s=" . (isset($_GET['s']) ? urlencode($_GET['s']) : "") . "&t=" . (isset($_GET['t']) ? urlencode($_GET['t']) : "") . "&favorite=" . ($isFavorite ? "false" : "true");
			$txt = ($isFavorite ? Core::getTranslation("add_favorite") : Core::getTranslation("remove_favorite"));
			echo '<a href="' . $url . '">' . $txt . '</a>';
		}
	}

	// Init the system
	KoalecteurSystem::initSystem();

	if(isset($_GET['favorite']))
	{
		if($_GET['favorite'] == "true")
		{
			KoalecteurSystem::addToFavorite();
		}
		else if($_GET['favorite'] == "false")
		{
			KoalecteurSystem::removeFromFavorite();
		}
	}
?>
