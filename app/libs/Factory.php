<?php 

namespace simplerest\libs;

class Factory {
	static function response() {
		return \core\Response::getInstance();
	}

	static function request() {
		return \core\Request::getInstance();
	}
}
