<?php

namespace SimpleRest\controllers;

class PostsController extends MyController
{
    function index(){
        return 'Hola desde '.__FUNCTION__;
    }
    
    function get($id_post){
        return "Trayendo post con id=$id_post";
    }

    function list(){
        return 'Hola desde '.__FUNCTION__;
    }

}