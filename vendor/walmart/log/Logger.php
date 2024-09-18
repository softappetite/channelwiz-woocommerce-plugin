<?php
namespace App\Services\Walmart\log;

interface Logger {
	
	public function log($message, $error = false);
}
?>