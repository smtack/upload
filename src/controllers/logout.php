<?php

use Core\Redirect;

$user = new Models\User();

$user->logout();

Redirect::to(BASE_URL);
