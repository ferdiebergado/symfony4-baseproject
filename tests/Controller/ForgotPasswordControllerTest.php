<?php

declare(strict_types=1);

namespace App\Tests\Controller;

// use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ForgotPasswordControllerTest extends AbstractControllerWebTestCase
{
    public function testGetEmail(): void
    {
        $data = [
            'email' => 'abc@123.com',
            'plainPassword' => ['first' => 'testtest', 'second' => 'testtest'],
        ];
        $crawler = $this->client->request('GET', '/password/forgot');

        $form = $crawler->selectButton('SEND PASSWORD RESET LINK')->form();

        $form['forgot_password[email]'] = $data['email'];

        $this->client->submit($form);
        $content = $this->client->getResponse()->getContent();

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertContains('A password reset confirmation was sent to your email.', $content);
    }

    public function testResetPassword(): void
    {
        $password = 'updatedpassword';
        $crawler = $this->client->request('GET', '/password/reset/');

        $form = $crawler->selectButton('CHANGE PASSWORD')->form();

        $form['reset_password[plainPassword][first]'] = $password;
        $form['reset_password[plainPassword][second]'] = $password;

        $this->client->submit($form);
        $content = $this->client->getResponse()->getContent();

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertContains('Your password was reset successfully.', $content);
    }
}
