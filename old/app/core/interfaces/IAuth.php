<?php

namespace simplerest\core\interfaces;

interface IAuth {
    function login();
    function token();
    function signup();
    function check();   
}