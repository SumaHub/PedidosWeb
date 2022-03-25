<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class MailerController extends EsmtpTransport
{
    public function __construct(string $username, string $password, EventDispatcherInterface $dispatcher = null, LoggerInterface $logger = null)
    {
        parent::__construct('smtp.dreamhost.com', 465, true, $dispatcher, $logger);

        $this->setUsername($username);
        $this->setPassword($password);
    }
}
