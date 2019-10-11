<?php

namespace simplerest\core\interfaces;

interface IAuth {
    function login();
    function refresh();
    function signup();
    function check();   
}