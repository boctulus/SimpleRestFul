<?php

require_once "my_controller.php";

class PostsController extends MyController
{
    function index(){
        echo 'Hola desde '.__FUNCTION__;
    }
    
    function get($id_post){
        echo "Trayendo post con id=$id_post";
    }

    function list(){
        echo 'Hola desde '.__FUNCTION__;
    }

}