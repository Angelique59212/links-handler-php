<?php

namespace App\Controller;

use JetBrains\PhpStorm\NoReturn;

class UserController extends AbstractController
{
    #[NoReturn] public function index()
    {
        $this->render('user/register');
    }
}
