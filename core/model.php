<?php

require_once 'reflection_helper.php';

class Model {
	
	protected $missing_properties = [];
	
	function getMissingProperties() {
		return $this->missing_properties;
	}
	
	function has_properties($props, array $excluded = []){
		if (is_object($props))
			$props = (array) $props;
		
		$props = array_keys($props);
		
		if (empty($props))
			throw new InvalidArgumentException("No properties!");
		
		$expected_props  = getPropertiesNames($this); 
		
		$success = true;
		foreach ($expected_props as $exp){
			if (!in_array($exp, $props) && !in_array($exp, $excluded)){
				$this->missing_properties[] = $exp; 
				$success = false;
			}	
		}
		
		//debug($this->missing_properties);
		
		return $success;
	}
	
}