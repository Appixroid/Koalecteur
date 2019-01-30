<?php
	require_once("RSS.php");
		
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
			self::$sources = explode(";", file_get_contents("sources.csv"));
			
			self::$rss_array = array();
			foreach(self::$sources as $source)
			{
				if(strlen($source) > 4)
				{
					array_push(self::$rss_array, new RSS($source));
				}
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
				$isset = isset($_GET['s']);
				
				if(($isset && !empty($_GET['s']) && $rss->getRssSourceLink() == $_GET['s']) || (!$isset))
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
				include("templates/news.php");
			}
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
						<input type="text" placeholder="Search" value="' . $word . '" name="q"/>
						<input type="date" value="' . $date . '" name="t"/>
						<select name="s">';
			
			foreach(self::$sources as $source)
			{
				if(strlen($source) > 4)
				{
					$selected = "";
					if(isset($_GET['s']) && !empty($_GET['s']) && $source == $_GET['s'])
					{
						$selected = "selected";
					}
					
					$form .= '<option value="' . $source . '" ' . $selected . '>' . parse_url($source, PHP_URL_HOST) . '</option>';
				}
			}

			$form .= '	</select>
						<input type="submit" value="Filter" /> 
					</form>';
					
			echo $form;
		}
	}
	
	// Init the system
	KoalecteurSystem::initSystem();
?>
