<?php

use simplerest\core\Route;
use simplerest\libs\Debug;

$route = Route::getInstance();

// http://az.lan/calc/sum/4/5
Route::get('calc/sum', function($a, $b){
    return "La suma de $a y $b da ". ($a + $b);
})->where(['a' => '[0-9]+', 'b' =>'[0-9]+']);


// http://az.lan/saludador/sp/hi/Juan/Carlos/44
Route::get('saludador/sp/hi/', function($nombre, $apellido, $edad){
    return "Hola ". ($edad > 30 ? 'Sr. ' : '') . "$nombre $apellido";
})->where(['edad' => '[0-9]+', 'nombre' =>'[a-zA-Z]+', 'apellido' =>'[a-zA-Z]+']);

// http://az.lan/saludador/en/hi/Juan/Carlos/44
Route::get('saludador/en/hi/', function($nombre, $apellido, $edad){
    return "Hello ". ($edad > 30 ? 'Mr. ' : '') . "$apellido $nombre";
})->where(['edad' => '[0-9]+', 'nombre' =>'[a-zA-Z]+', 'apellido' =>'[a-zA-Z]+']);

// http://az.lan/tonterias
Route::get('tonterias',  'DumbController');

// http://az.lan/chatbot/hi
Route::get('chatbot/hi', 'DumbController@hi')
->where(['name' => '[a-zA-Z]+']);  // <-- where() no implementado con controladores !

// http://az.lan/cosas/67
Route::delete('cosas', function($id){
    return "Deleting cosa con id = $id";
});


Route::compile();