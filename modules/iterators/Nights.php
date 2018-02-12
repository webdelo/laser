<?php
namespace modules\iterators;
class Nights implements \Iterator 
{
	private $position = 0;
	private $nights     = array();

	public function __construct($startDate, $endDate)
	{
		$this->setNightsLine($startDate, $endDate);
	}
	
	private function setNightsLine($startDate, $endDate)
	{
		$dayUnix   = \core\utils\Dates::$secondsInDay;
		$startUnix = \core\utils\Dates::dateTimeToTimestamp($startDate);
		$endUnix   = \core\utils\Dates::dateTimeToTimestamp($endDate);
		
		for($day=$startUnix; $day<$endUnix; $day=$day+$dayUnix ) {
			$this->nights[] = $day;
		}
	}

    function rewind() {
        $this->position = 0;
    }

    function current() {
        return $this->nights[$this->position];
    }

    function key() {
        return $this->position;
    }

    function next() {
        ++$this->position;
    }

    function valid() {
        return isset($this->nights[$this->position]);
    }
	
	public function count()
	{
		return sizeof($this->nights);
	}
}

