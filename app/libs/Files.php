<?php 

namespace simplerest\libs;

class Files {

	static function logger($data, $file = 'log.txt'){		
		if (is_array($data) || is_object($data))
			$data = json_encode($data);
		
		$data = date("Y-m-d H:i:s"). "\t" .$data;

		return file_put_contents(LOGS_PATH . $file, $data. "\n", FILE_APPEND);
	}
	
    // @author Federkun
    static function mkdir_ignore($dir){
		if (!is_dir($dir)) {
			if (false === @mkdir($dir, 0777, true)) {
				throw new \RuntimeException(sprintf('Unable to create the %s directory', $dir));
			}
		}
	}

}    