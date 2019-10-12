<?php

declare(strict_types=1);

namespace App\Tests\Security;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginFormAuthenticatorTest extends WebTestCase
{
    public function test(): void
    {
        $data = [
            'email' => 'abc@123.com',
            'plainPassword' => 'abc@1234',
        ];

        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Sign in')->form();

        $form['email'] = $data['email'];
        $form['password'] = $data['plainPassword'];

        $client->submit($form);
        // $content = $client->getResponse()->getContent();

        $this->assertTrue($client->getResponse()->isSuccessful());
        // $this->assertContains('Your account has been created. Please verify your email.', $content);
    }
}
