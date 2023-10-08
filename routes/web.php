<?php

use DLRoute\Requests\DLRoute;
use DLUnire\Controllers\TestController;

DLRoute::get('/', [TestController::class, 'index']);