<?php
namespace App\Services\Walmart\log;

class FileLogger implements Logger {
	
	private $info;
	
	private $error;
	
	public function log($message, $error = false) {
		error_log($message."\n", 3, ($error ? $this->error : $this->info));
	}
	
	public function __construct($infoLogFile, $errorLogFile) {
		$this->info = $infoLogFile;
		$this->error = $errorLogFile;
	}
}