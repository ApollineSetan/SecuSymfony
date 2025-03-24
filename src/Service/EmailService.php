<?php

namespace App\Service;

class EmailService 
{

    public function __construct(
            private readonly string $emailUser,
            private readonly string $emailPassword,
            private readonly string $emailSmtp,
            private readonly int $emailPort
    ) {}

    public function test() : string {
        return "USERNAME : " . $this->emailUser .
        "PASSWORD : " . " SMTP : " . $this->emailSmtp .
        " PORT : " . $this->emailPort;
    }
}