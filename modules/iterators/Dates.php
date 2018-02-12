<?php
namespace modules\iterators;
class Dates implements \Iterator 
{
	private $position = 0;
	private $days     = array();

	public function __construct($startDate, $endDate)
	{
		$this->setDaysLine($startDate, $endDate);
	}
	
	private function setDaysLine($startDate, $endDate)
	{
		$dayUnix   = \core\utils\Dates::$secondsInDay;
		$startUnix = \core\utils\Dates::dateTimeToTimestamp($startDate);
		$endUnix   = \core\utils\Dates::dateTimeToTimestamp($endDate);
		
		for($day=$startUnix; $day<=$endUnix; $day=$day+$dayUnix ) {
			$this->days[] = $day;
		}
	}

    function rewind() {
        $this->position = 0;
    }

    function current() {
        return $this->days[$this->position];
    }

    function key() {
        return $this->position;
    }

    function next() {
        ++$this->position;
    }

    function valid() {
        return isset($this->days[$this->position]);
    }
	
	public function count()
	{
		return sizeof($this->days);
	}
}

