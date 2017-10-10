<?php
require_once 'vendor/autoload.php';


use Mail;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use Illuminate\Events\Dispatcher;
use Illuminate\Mail\Mailer;
use Illuminate\Log\Writer;
use Monolog\Logger;

$app = new \Slim\Slim();
$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);
$app->get('/', function ()
{
    $logger = new Writer(new Logger('local'));
    // note: make sure log file is writable
    $logger->useFiles('../../logs/laravel.log');
    // chose a transport (SMTP, PHP Mail, Sendmail, Mailgun, Maindrill, Log)
    // $transport = SmtpTransport::newInstance(getenv('SMTP_HOST'), getenv('SMTP_PORT'));
    $transport = MailTransport::newInstance();
    // $transport = SendmailTransport::newInstance('/usr/sbin/sendmail -bs');
    // $transport = new MailgunTransport(getenv('MAILGUN_SECRET'), getenv('MAILGUN_DOMAIN'));
    // $transport = new MandrillTransport(getenv('MANDRILL_SECRET'));
    // $transport = new LogTransport($logger->getMonolog());
    // SMTP specific configuration, remove these if you're not using SMTP
    $transport->setUsername(getenv('SMTP_USERNAME'));
    $transport->setPassword(getenv('SMTP_PASSWORD'));
    $transport->setEncryption(true);
    $swift    = new SwiftMailer($transport);
    $finder   = new FileViewFinder(new Filesystem, ['views']);
    $resolver = new EngineResolver;
    // determine which template engine to use
    $resolver->register('php', function()
    {
        return new PhpEngine;
    });
    $view   = new Factory($resolver, $finder, new Dispatcher());
    $mailer = new Mailer($view, $swift);
    $mailer->setLogger($logger);
    // $mailer->setQueue($app['queue']); // note: queue functionality is not available if the queue module is not set
    // $mailer->setContainer($app);      // note: the message builder must be a callback if the container is not set
    // pretend method can be used for testing
    $mailer->pretend(false);
    // prepare email view data
    $data = [
        'greeting' => 'You have arrived, girl.',
    ];
    $mailer->send('email.welcome', $data, function($message)
    {
        $message->from(getenv('MAIL_FROM_ADDRESS'), 'Code Guy');
        $message->to("rubemmota89@gmail.com", 'Keira Knightley');
        $message->subject('Yo!');
    });
    var_dump('Done');
});
$app->run();
