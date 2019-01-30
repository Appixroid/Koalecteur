<?php
	
	class RSS
	{
		private $rssRoot;
		
		/**
		 * Constructor that build a RSS Object from a RSS file
		 */
		public function __construct($file)
		{
			$this->rssRoot = simplexml_load_file($file);
		}
		
		////////////////
		/// RSS Flux ///
		////////////////
		
		/**
		 * Return the SimpleXMLElement Object of the RSS channel
		 */
		public function getChannel()
		{
			return $this->rssRoot->channel;
		}
		
		/**
		 * Return the Title of the RSS Flux
		 */
		public function getRssTitle()
		{
			return $this->getChannel()->title->__toString();
		}
		
		/**
		 * Return the Description of the RSS Flux
		 */
		public function getRssDescription()
		{
			return $this->getChannel()->description->__toString();
		}
		
		/**
		 * Return the Source Link of the RSS Flux
		 */
		public function getRssSourceLink()
		{
			return $this->getChannel()->link->__toString();
		}
		
		/**
		 * Return the Publication Date of the RSS Flux (if Exist)
		 */
		public function getRssPublicationDate()
		{
			return $this->getChannel()->pubDate->__toString();
		}
		
		/**
		 * Return the Last Modification Date of the RSS Flux (if Exist)
		 */
		public function getRssLastBuildDate()
		{
			return $this->getChannel()->lastBuildDate->__toString();
		}
		
		/**
		 * Return the Associated Image of the RSS Flux (if Exist)
		 */
		public function getRssImage()
		{
			return $this->getChannel()->image->__toString();
		}
		
		/**
		 * Return the Language of the RSS Flux (if Exist)
		 */
		public function getRssLanguage()
		{
			return $this->getChannel()->language->__toString();
		}
		
		/**
		 * Return the Associated Media of the RSS Flux (if Exist)
		 */
		public function getRssMedia()
		{
			return $this->getChannel()->enclosure->__toString();
		}
		
		//////////////////
		/// News Items ///
		//////////////////
		
		/**
		 * Return the Array of the news Items
		 */
		public function getNewsArray()
		{
			return $this->getChannel()->item;
		}
		
		/**
		 * Return the amount of News Items in the RSS Flux
		 */
		public function getNewsCount()
		{
			return count($this->getNewsArray());
		}
		
		/**
		 * Return the SimpleXMLElement Object of the i-nth News Item 
		 */
		public function getNthNews($i)
		{
			return $this->getNewsArray()[$i];
		}
		
		/**
		 * Return the Title of the i-nth News
		 */
		public function getNewsTitle($i)
		{
			return $this->getNthNews($i)->title->__toString();
		}
		
		/**
		 * Return the Link of the i-nth News
		 */
		public function getNewsLink($i)
		{
			return $this->getNthNews($i)->link->__toString();
		}
		
		/**
		 * Return the Publication Date of the i-nth News
		 */
		public function getNewsPublicationDate($i)
		{
			return $this->getNthNews($i)->pubDate->__toString();
		}
		
		/**
		 * Return the Description of the i-nth News
		 */
		public function getNewsDescription($i)
		{
			return $this->getNthNews($i)->description->__toString();
		}
		
		/**
		 * Return the Unique Id of the i-nth News
		 */
		public function getNewsUniqueId($i)
		{
			return $this->getNthNews($i)->guid->__toString();
		}
		
		/**
		 * Return the Author of the i-nth News (if Exist)
		 */
		public function getNewsAuthor($i)
		{
			return $this->getNthNews($i)->author->__toString();
		}
		
		/**
		 * Return the Category of the i-nth News (if Exist)
		 */
		public function getNewsCategory($i)
		{
			return $this->getNthNews($i)->category->__toString();
		}
		
		/**
		 * Return the Comment of the i-nth News (if Exist)
		 */
		public function getNewsComment($i)
		{
			return $this->getNthNews($i)->comments->__toString();
		}
		
		/**
		 * Return an array with the type and the url of the Enclosure of the i-nth News (if Exist)
		 */
		public function getNewsEnclosure($i)
		{
			return array($this->getNthNews($i)->enclosure->attributes()['type'], $this->getNthNews($i)->enclosure->attributes()['url']);
		}
	}
?>
