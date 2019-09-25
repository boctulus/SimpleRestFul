<?php

namespace simplerest\core\interfaces;

interface IAuth {
    function login();
    function token_renew();
    function signup();
    function check_auth();   
}