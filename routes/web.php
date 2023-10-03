<?php

use DLRoute\Requests\DLRoute;
use DLUnire\Controllers\TestController;


DLRoute::get('/', [TestController::class, 'index']);

// DLRoute::post('/password/{password}', [TestController::class, 'index']);
DLRoute::post('/login', [TestController::class, 'auth']);


DLRoute::post('/google/test', [TestController::class, 'recaptcha']);