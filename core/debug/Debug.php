<?php
namespace core\debug;
class Debug {
	static protected $object = null;
	static public $varDumpData = array();
	public $errorsList     = array(); // Errors from module ErrorHandler
	
	private $config                 = array();
	private $color_array            = array('#8B1A1A', '#CD2626', '#EE2C2C', '#FF3030'); // Colors for backround highlight points
	private $exceeding_point_color  = '#BBFFFF'; // Color for backround highlight point where current timer > allowable timer per current point
	private $exceeding_points_color = '#FFBBFF'; // Color for backround highlight point where current timer > allowable timer per all point
	
	private $points    = array(); // All point data, Description and Timer per point
	private $file      = array(); // All point data about File
	private $line      = array(); // All point data about Line
	private $functions = array(); // All point data about Function or Method
	private $class     = array(); // All point data about Class
	private $method    = array(); // All point data about Method
	
	private $double_points  = array(); // All data about segments
	private $files_in_debug = array(); // Data about all files involved in debug

	private $execute_timer;       // General timer's result
	private $current_timer;       // Current timer's result
	private $prev_timer;          // Prev timer's result
	private $point_execute_timer; // Current execute timer's result
	private $color_indicator;     // Color indicator for any point

	function setConfig($config) {
		$this->config = $config;
	}

	function __construct ($file=null, $line=null, $functions=null, $class=null, $method=null) {
		$this->setData($file, $line, $functions, $class, $method);
	}

	static public function getInstance ($file=null, $line=null, $functions=null, $class=null, $method=null) {
		if (is_null(self::$object))
			self::$object = new Debug($file, $line, $functions, $class, $method);
		return self::getObject()->setData($file, $line, $functions, $class, $method);
	}

	static function getObject(){
		return self::$object;
	}

	private function setData($file=null, $line=null, $functions=null, $class=null, $method=null){
		$this->file      = $this->getFileDetails($file, $line);
		$this->line      = $line;
		$this->functions = $functions;
		$this->class     = $class;
		$this->method    = $method;
		return self::$object;
	}
	
	private function getFileDetails ($file, $lines=null, $execute_timer=null) {
		return array(
			'full_name'     => $file,
			'truncate_name' => str_replace(dirname($file), '', $file),
			'dir_name'      => dirname($file),
			'lines'         => $lines,
			'execute_timer' => $execute_timer
		);
	}

	public function start () {
		return $this->point('Debugging start');
	}

	public function end () {
		return $this->point('Debugging end');
	}

	public function point($description, $max_execute_time = null) {
		$this->setPrevTimer();
		$this->setCurrentTimer();
		$this->setPointExecuteTimer();
		$data = array(
			'description'      => $description,
			'point_time'       => date('H:i:s'),
			'timer'            => $this->current_timer,
			'file'             => $this->file,
			'line'             => $this->line,
			'functions'        => $this->functions,
			'class'            => $this->class,
			'method'           => $this->method,
			'execute_timer'    => $this->point_execute_timer,
			'max_execute_time' => $max_execute_time
		);
		$this->points[] = $data;
		$this->checkCurrentPointTimer($max_execute_time);
		return self::$object;
	}

	private function setPrevTimer () {
		$prev = end($this->points);
		if ( isset($prev['timer']) ) {
			$this->prev_timer = $prev['timer'];
		} else {
			$this->prev_timer = false;
		}
	}

	private function setCurrentTimer () {
		list($usec, $sec) = explode(" ", microtime()); 
		$this->current_timer = round( ((float)$usec + (float)$sec) , $this->config['round_timer']);
	}
	
	private function setPointExecuteTimer () {
		if ($this->prev_timer!==false) {
			$this->point_execute_timer = round($this->current_timer - $this->prev_timer, $this->config['round_timer']);
		} else {
			$this->point_execute_timer = 0;
		}
	}

	private function setMailContent ($mailer_name) {
		$this->setColorIndicator();
		$this->setAllExecuteTimer();
		$this->config['mailContent'] = $this->getContent('/mailers/'.$mailer_name);
	}
	
	private function getContent ($file_name) {
		global $SysConf;
		ob_start();
		include ($this->config['document_root'].'/'.$file_name);
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
	
	public function showContent($file_name)
	{
		echo $this->getContent($file_name);
	}

	private function sendMail() {
		include_once ($this->config['document_root'].'/mail.php');
		$m = new mail;
		$m->From($this->config['mailFrom']);
		$m->To($this->config['mailTo']);
		$m->Subject($this->config['mailSubject'].' '.$this->config['projectName']);
		$m->Body($this->config['mailContent']);
		$m->Send();
		unset($m);
	}
	
	public function setResult () {
		if (!empty($this->config['log'])) {
			foreach ($this->config['log'] as $key=>$methodType) {
				$method = 'log'.$methodType;
				if (in_array($method, get_class_methods($this)))
					$this->$method();
			}
		} else {
			trigger_error('Error! You don\'t define log types!', E_USER_WARNING);
		}
	}

	private function logMail () {
		$this->point('Send mails fo developers');
		$this->setColorIndicator();
		$this->setAllExecuteTimer();
		$this->setFilesInDebug();
		$this->checkPointsTimer();
		$this->config['mailSubject'] = 'Results of calculations. '.$this->config['projectName'] ;
		$this->config['mailContent'] = $this->getContent('/mailers/resultMail.mail');
		$this->sendMail();
	}

	private function logScreen () {
		$this->point('Show results on the screen');
		$this->setColorIndicator();
		$this->setAllExecuteTimer();
		$this->setErrorList();
		$this->setFilesInDebug();
		$this->checkPointsTimer();
		$this->setDoublePoints();
		$shell = new DeveloperShell($this);
		$shell->show();
	}
	
	private function logFile () {}
	private function logDB () {}

	private function setAllExecuteTimer () {
		if ($this->checkAllowablyMaxPointTimer()) $color = 'red';
		$timer = round($this->getLastPoint() - $this->getFirstPoint(), $this->config['round_timer']);
		$this->execute_timer = array(
			'timer'=> (isset($timer)) ? $timer : '' , 
			'color'=>(isset($color)) ? $color : '' 
		);
	}
	
	private function getFirstPoint () {
		$first = reset($this->points);
		return $first['timer'];
	}

	private function getLastPoint () {
		$last = end($this->points);
		return $last['timer'];
	}

	private function setFilesInDebug () {
		$execute_timer = array();
		foreach ($this->points as $key=>$value) {
			$files[] = $value['file']['full_name'];
			$lines[$value['file']['full_name']][] = array( 
				'line'            => $value['line'], 
				'description'     => $value['description'],
				'execute_timer'   => $value['execute_timer'],
				'color_indicator' => $value['color_indicator'],
				'background'      => $value['background'],
			);
			$execute_timer[$value['file']['full_name']] = (isset($execute_timer[$value['file']['full_name']])) ? $execute_timer[$value['file']['full_name']]+ $value['execute_timer'] : 0;
		}
		$files = array_unique($files);
		foreach ($files as $key=>$file) {
			$this->files_in_debug[] = $this->getFileDetails($file, $lines[$file], $execute_timer[$file]);
		}
	}
	
	private function setColorIndicator () {
		foreach ($this->points as $key=>$point) {
			$timers[] = $point['execute_timer'];
			$max_execute_times[] = $point['max_execute_time'];
		}

		natsort($timers);
		$timers = array_reverse($timers, true);

		$counter = 0;
		foreach ($timers as $point_key=>$timer)
			$colors[$point_key] = $this->color_array[$counter++];

		$counter = 0;
		foreach ($max_execute_times as $point_key=>$max_time) {
			if ($timers[$point_key] >= $this->config['max_point_timer']) 
				$backgrounds[$point_key] = $this->exceeding_points_color;
			if ($max_time)
				if ($timers[$point_key] >= $max_time)
					$backgrounds[$point_key] = $this->exceeding_point_color;
		}
		foreach ($this->points as $key=>$point) {
			$this->points[$key] = array_merge( 
					$this->points[$key], 
					array( 
						'color_indicator'=> (isset($colors[$key])) ? $colors[$key] : '',
						'background' => (isset($backgrounds[$key])) ? $backgrounds[$key] : '' 
					)
			);
		}
	}

	private function setDoublePoints () {
		foreach ($this->points as $key=>$point) {
			$point_data[$point['description']][$key] = $point;
			$timers[$point['description']][] = $point['timer'];
		}
		array_multisort($timers, SORT_NUMERIC, SORT_ASC);
		foreach ($timers as $description=>$timer) {
			if ( sizeof($timer)%2==0 ) {
				$execute_timers[$description] = round( ($timer[1] - $timer[0]) , $this->config['round_timer']);
			} else {
				unset($timers[$description]);
			}
		}
		if(isset($execute_timers)) {
			foreach ($execute_timers as $description=>$timer) {
				$data = array(
					'description'   => $description,
					'execute_timer' => $timer,
				);
				$data = array_merge($data, array('details'=>$point_data[$description]));
				$this->double_points[] = $data;
			}
		}
	}
	
	private function getMaxPointTimer () {
		foreach ($this->points as $key=>$point)
			$timers[] = $point['execute_timer'];
		sort($timers);
		return array_pop($timers);
	}	
	
	private function checkCurrentPointTimer($point_timer=null) {
		if ($this->checkAllowablyCurrentMaxPointTimer($point_timer) && $this->checkSendMail()) {
			$this->setMailContent('debug_point.mail');
			$this->sendMail();
		}
	}

	private function checkAllowablyCurrentMaxPointTimer ($point_timer=null) {
		if ($point_timer)
			return ($this->point_execute_timer >= $point_timer) ? true: false ;
		return ($this->point_execute_timer >= $this->config['max_point_timer']) ? true: false ;
	}

	private function checkPointsTimer() {
		if ($this->checkAllowablyMaxPointTimer() && $this->checkSendMail()) {
			$this->setMailContent('debug_points.mail');
				$this->sendMail();
		}
	}

	private function checkAllowablyMaxPointTimer () {
		return ($this->getMaxPointTimer() >= $this->config['max_point_timer']) ? true: false ;
	}

	private function checkSendMail () {
		return (in_array('Mail', $this->config['log'])) ? true : false ;
	}
	
	
	static public function varDump($var) {
		self::$varDumpData[] = $var;
	}
	
	public function setErrorList () {
		$this->errorsList = ErrorHandler::$errors;
	}
}
?>
