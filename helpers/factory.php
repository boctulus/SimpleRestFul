<?php 

if (! function_exists('response')) {
	function response() {
		return Response::getInstance();
	}
}

if (! function_exists('request')) {
	function request() {
		return Request::getInstance();
	}
}