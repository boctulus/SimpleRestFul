<?php

namespace simplerest\core\interfaces;

interface IValidator {
    function validate(array $rules, array $data, array $ignored_fields = null, bool $as_string = false);
}