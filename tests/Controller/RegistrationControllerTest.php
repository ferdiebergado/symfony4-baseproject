<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testRegister(): void
    {
        $data = [
            'email' => 'test@example.com',
            'plainPassword' => ['first' => 'testtest', 'second' => 'testtest'],
        ];

        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('Register')->form();

        $form['registration_form[email]'] = $data['email'];
        $form['registration_form[plainPassword][first]'] = $data['plainPassword']['first'];
        $form['registration_form[plainPassword][second]'] = $data['plainPassword']['second'];
        $form['registration_form[agreeTerms]'] = true;

        $client->submit($form);
        $content = $client->getResponse()->getContent();

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertContains('Your account has been created. Please verify your email.', $content);
    }
}
