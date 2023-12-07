<?php

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mailer\Transport\Smtp\Stream\SocketStream;
use Symfony\Component\Mime\Address;

$container->loadFromExtension('framework', [
    'mailer' => [
        'dsn' => 'smtp://smtp.gmail.com:587',
        'transport' => EsmtpTransport::class,
        'username' => 'alexisbateaux',
        'password' => 'vlskzoknjkkjighh',
        'stream_options' => [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ],
    ],
]);

$container->register(MailerInterface::class, EsmtpTransport::class)
    ->setFactory([Transport::class, 'fromDsn'])
    ->setArgument('$dsn', '%env(MAILER_DSN)%')
    ->setArgument('$stream', new SocketStream(['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]]))
    ->setPublic(true);

$container->register('App\Service\MyMailer')
    ->setArgument('$mailer', MailerInterface::class);