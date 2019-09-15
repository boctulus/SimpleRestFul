<?php 

if (! function_exists('response')) {
	function response() {
		return \Core\Response::getInstance();
	}
}

if (! function_exists('request')) {
	function request() {
		return \Core\Request::getInstance();
	}
}
