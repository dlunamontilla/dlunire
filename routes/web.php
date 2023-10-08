<?php

use DLRoute\Requests\DLRoute;
use DLUnire\Controllers\TestController;

DLRoute::get('/', [TestController::class, 'index']);

DLRoute::get('/test', [TestController::class, 'test']);