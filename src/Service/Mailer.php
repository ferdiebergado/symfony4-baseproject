<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Mailer\MailerInterface;

/**
 * Mailer Service
 */
class Mailer
{
    private const DEFAULT_SUBJECT = 'Welcome';
    private const DEFAULT_TEMPLATE = 'welcome';

    /** @var MailerInterface */
    private $mailer;

    /** @var ContainerBagInterface */
    private $containerBag;

    public function __construct(MailerInterface $mailer, ContainerBagInterface $containerBag)
    {
        $this->mailer = $mailer;
        $this->containerBag = $containerBag;
    }

    /**
     * Send an email.
     *
     * @param iterable $params
     */
    public function send(string $to, ?string $subject = null, ?string $template = null, iterable $params = []): void
    {
        if ($subject === null) {
            $subject = self::DEFAULT_SUBJECT;
        }

        if ($template === null) {
            $template = self::DEFAULT_TEMPLATE;
        }

        $from = $this->containerBag->get('app.admin_email');
        $email = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)

            // path of the Twig template to render
            ->htmlTemplate('emails/' . $template . '.html.twig')

            // pass variables (name => value) to the template
            ->context(['params' => $params]);

        $this->mailer->send($email);
    }
}
