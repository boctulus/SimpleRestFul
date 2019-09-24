<?php 

namespace simplerest\libs;

class Factory {
	static function response() {
		return \simplerest\core\Response::getInstance();
	}

	static function request() {
		return \simplerest\core\Request::getInstance();
	}

	static function check_auth(){
		$auth = new \simplerest\controllers\AuthController();
        return $auth->check_auth();
	}
}
