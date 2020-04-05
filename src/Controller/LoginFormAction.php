<?php

namespace Snowdog\DevTest\Controller;

class LoginFormAction
{

    public function execute()
    {
        if (isset($_SESSION['login'])) {
            header('HTTP/1.0 403 Forbidden'); 
        }

        require __DIR__ . '/../view/login.phtml';
    }
}