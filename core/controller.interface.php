<?php

Interface IController {
    function view(string $view_path, array $vars_to_be_passed);
}