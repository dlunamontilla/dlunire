<?php

use Boot\Project;
use Framework\Errors\DLExceptionHandler;

include dirname(__DIR__) . "/vendor/autoload.php";

set_exception_handler([DLExceptionHandler::class, 'handle']);

Project::run();