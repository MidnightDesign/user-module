<?php

namespace Midnight\UserModule\Controller;

use Midnight\User\Entity\User;
use Midnight\User\Service\UserService;
use Zend\Mvc\Controller\AbstractConsoleController;

class UserConsoleController extends AbstractConsoleController
{
    /** @var UserService */
    private $userService;

    /**
     * UserConsoleController constructor.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function createUserAction()
    {
        $email = $this->params('email');
        $console = $this->getConsole();
        $console->write('Password: ');
        $password = $console->readLine();
        $user = $this->userService->register($email, $password);
        if ($user instanceof User) {
            $output = sprintf('The new user\'s ID is %s.', $user->getId());
        } else {
            $output = 'The user was successfully created.';
        }
        $console->writeLine($output);
    }
}