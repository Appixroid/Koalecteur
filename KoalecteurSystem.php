<?php
	require_once("RSS.php");
		
	class KoalecteurSystem
	{
		public static $sources;
		public static $rss_array;
		public static $news_array;
	
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
		}
		
		private static function pushAfterDate($item)
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
						$placed = true;
					}
				}
			}
			
			if(!$placed)
			{
				array_push(self::$news_array, $item);
			}
		}
	
		public static function includeNews()
		{
			for($j = 0; $j < count(self::$rss_array); $j++)
			{
				$rss = self::$rss_array[$j];
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
								 "comment" => $rss->getNewsComment($i)));
				}
			}
			
			for($i = 0; $i < count(self::$news_array); $i++)
			{
				$_NEWS = self::$news_array[$i];
				include("templates/news.php");
			}
		}
	}
	
	KoalecteurSystem::initSystem();
?>
