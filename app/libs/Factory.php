<?php 

namespace SimpleRest\libs;

class Factory {
	static function response() {
		return \Core\Response::getInstance();
	}

	static function request() {
		return \Core\Request::getInstance();
	}
}
