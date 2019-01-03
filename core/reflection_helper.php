<?php

/** 
* Recursive function to get an associative array of class properties by property name => ReflectionProperty() object 
* including inherited ones from extended classes 
* @param string $className Class name 
* @param string $types Any combination of <b>public, private, protected, static</b> 
* @return array 
*
* @author muratyaman at gmail dot com
*/ 
function getClassProperties($className, $types='public'){ 
    $ref = new ReflectionClass($className); 
    $props = $ref->getProperties(); 
    $props_arr = array(); 
	
    foreach($props as $prop){ 
        $f = $prop->getName(); 
        
        if($prop->isPublic() and (stripos($types, 'public') === FALSE)) continue; 
        if($prop->isPrivate() and (stripos($types, 'private') === FALSE)) continue; 
        if($prop->isProtected() and (stripos($types, 'protected') === FALSE)) continue; 
        if($prop->isStatic() and (stripos($types, 'static') === FALSE)) continue; 
        
        $props_arr[$f] = $prop; 
    } 
	
	/*
    if($parentClass = $ref->getParentClass()){ 
        $parent_props_arr = getClassProperties($parentClass->getName());//RECURSION 
        if(count($parent_props_arr) > 0) 
            $props_arr = array_merge($parent_props_arr, $props_arr); 
    } 
	*/
    return $props_arr; 
} 

/*
	Plain version of getClassProperties()
*/
function getPropertiesNames($className, $types = 'public'){
	$arr = getClassProperties($className, $types);
	
	$out = [];
	foreach ($arr as $o){
		$out[] = $o->name;
	}
	return array_values($out);
}

function has_properties($obj, array $props){
	if (empty($props))
		throw new InvalidArgumentException("No properties!");
	
	$expected_props  = getPropertiesNames($obj);
	
	foreach ($expected_props as $exp){
		if (!in_array($exp, $props))
			return false;
	}
	
	return true;
}

