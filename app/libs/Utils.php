<?php 

namespace simplerest\libs;

class Utils {
	static function logger($data, $file = 'log.txt'){		
		if (is_array($data) || is_object($data))
			$data = json_encode($data);
		
		return file_put_contents(LOGS_PATH . $file, $data. "\n", FILE_APPEND);
	}
}