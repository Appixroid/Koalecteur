<?php
	require_once("RSS.php");
		
	class KoalecteurSystem
	{
		public static $sources;
		public static $rss_array;
		public static $news_array;
		public static $newsCount;
		
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
		
		private static function hasToBePush($item)
		{
			if(isset($_GET['q']) && !empty($_GET['q']))
			{
				$inTitle = strpos($item['title'], $_GET['q']) !== false;
				$inDesc = strpos($item['desc'], $_GET['q']) !== false;
				
				if(!$inTitle && !$inDesc)
				{
					return false;
				}
			}
			
			if(isset($_GET['t']) && !empty($_GET['t']))
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
									 "comment" => $rss->getNewsComment($i)),
									 "enclosure" => $rss->getNewsEnclosure($i));
					}
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
