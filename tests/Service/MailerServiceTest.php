<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Kernel;
use App\Service\Mailer;
use PHPUnit\Framework\TestCase;
use function md5;
use function random_bytes;

class MailerServiceTest extends TestCase
{
    public function testSend(): void
    {
        $kernel = new Kernel('test', true);
        $kernel->boot();

        $container = $kernel->getContainer();

        $mailer = $container->get(Mailer::class);

        $to = 'abc@gmail.com';
        $subject = 'Thank you for signing up';
        $template = 'signup';
        $token = md5(random_bytes(4));
        $url = '/verify/' . $token;

        $mailer->send($to, $subject, $template, ['url' => $url]);

        $this->assertTrue(true);
    }
}
