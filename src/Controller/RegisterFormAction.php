<?php

namespace Snowdog\DevTest\Controller;

class RegisterFormAction
{
    public function execute()
    {
        if (isset($_SESSION['login'])) {
            header('Location: /');
        }
        require __DIR__ . '/../view/register.phtml';
    }
}
