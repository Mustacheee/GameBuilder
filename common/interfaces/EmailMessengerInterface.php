<?php

namespace app\common\interfaces;

interface EmailMessengerInterface
{
    public function send(string $toEmail, string $subject, string $body);
}