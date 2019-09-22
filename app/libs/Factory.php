<?php 

namespace simplerest\libs;

use simplerest\core\Response;
use simplerest\core\Request;

class Factory {
	static function response() {
		return Response::getInstance();
	}

	static function request() {
		return Request::getInstance();
	}
}
