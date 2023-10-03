<?php

namespace Framework\Auth;

use DLTools\Auth\DLAuth;
use Framework\Config\Token;

abstract class AuthBase extends DLAuth {

    use Token;

    
}