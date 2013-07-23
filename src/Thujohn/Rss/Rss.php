<?php namespace Thujohn\Rss;

use Thujohn\Rss\Channel;

class Rss{
	protected $version = '';
	protected $encoding = '';
	protected $channel = array();
	protected $items = array();
	protected $limit = 0;

	public function feed($version, $encoding){
		$this->version = $version;
		$this->encoding = $encoding;
		return $this;
	}

	/**
	 * Parameters :
	 * - title (required)
	 * - link (required)
	 * - description (required)
	 * - language
	 * - copyright
	 * - managingEditor
	 * - webMaster
	 * - pubDate
	 * - lastBuildDate
	 * - category
	 * - generator
	 * - docs
	 * - cloud
	 * - ttl
	 * - image
	 * - rating
	 * - textInput
	 * - skipHours
	 * - skipDays
	 */
	public function channel($parameters){
		if (!array_key_exists('title', $parameters) || !array_key_exists('description', $parameters) || !array_key_exists('link', $parameters)){
			throw new \Exception('Parameter required missing : title, description or link');
		}

		$this->channel = $parameters;
		return $this;
	}

	/**
	 * Parameters :
	 * - title
	 * - link
	 * - description
	 * - author
	 * - category
	 * - comments
	 * - enclosure
	 * - guid
	 * - pubDate
	 * - source
	 */
	public function item($parameters){
		if (empty($parameters)){
			throw new \Exception('Parameter missing');
		}

		$this->items[] = $parameters;
		return $this;
	}
	
	public function limit($limit) {
		if(is_int($limit) and $limit > 0) {
			$this->limit = $limit;
		}
		return $this;
	}

	public function render(){
		$xml = new \SimpleXMLElement('<rss version="'.$this->version.'" encoding="'.$this->encoding.'"></rss>');

		$xml->addChild('channel');
		foreach ($this->channel as $kC => $vC){
			$xml->channel->addChild($kC, $vC);
		}

		$items = $this->limit > 0 ? array_slice($this->items, 0, $this->limit) : $this->items;
		foreach ($items as $item){
			$elem_item = $xml->channel->addChild('item');
			foreach ($item as $kI => $vI){
				$elem_item->addChild($kI, $vI);
			}
		}

		return $xml;
	}

	public function save($filename){
		return $this->render()->asXML($filename);
	}

	public function __toString(){
		return $this->render()->asXML();
	}
}
