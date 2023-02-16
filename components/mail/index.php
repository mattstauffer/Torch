<?php

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Factory\AppFactory;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\MailManager;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\View\FileViewFinder;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Mail\Factory;
use Illuminate\Support\Facades\Facade;
use Zeuxisoo\Whoops\Slim\WhoopsMiddleware;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Compilers\BladeCompiler;

require_once 'vendor/autoload.php';
require_once '../../src/App.php';
require_once '../../src/ExceptionHandler.php';

/**
 * Illuminate/mail
 *
 * @source https://github.com/illuminate/mail
 */

// Instantiate App
$app = AppFactory::create();

// Middleware
$app->add(new WhoopsMiddleware(['enable' => true]));

$app->get('/', function (Request $request, Response $response) {
    $container = new Container();
    
    $container['config'] = [
        'mail.driver' => null,
        'mail.default' => 'array', // used for testing
        'mail.mailers.array' => [
            'transport' => 'array'
        ],
        'mail.mailers.sendmail' => [
            'transport' => 'sendmail',
            'path' => '/usr/sbin/sendmail -bs'
        ],
        'mail.from' => 'person@example.com',
        'mail.reply_to' => 'person@example.com',
        'mail.to' => 'person@example.com',
        'mail.return_path' => 'person@example.com',
    ];
    
    $container->bind('exception.handler', ExceptionHandler::class);
    
    $container->singleton('mail.manager', function ($app) {
        return new MailManager($app);
    });
    
    $container->bind('mailer', function ($app) {
        return $app->make('mail.manager')->mailer();
    });
    
    $container->singleton('events', function ($app) {
        return new Dispatcher($app);
    });
    
    $container->alias('mail.manager', Factory::class);
    
    $container->singleton('view', function ($app) {
        // the following code is taken from the view example component
        
        // Note that you can set several directories where your templates are located
        $pathsToTemplates = [__DIR__ . '/templates'];
        $pathToCompiledTemplates = __DIR__ . '/compiled';

        // Dependencies
        $filesystem = new Filesystem;
        $eventDispatcher = $app['events'];

        // Create View Factory capable of rendering PHP and Blade templates
        $viewResolver = new EngineResolver;
        $bladeCompiler = new BladeCompiler($filesystem, $pathToCompiledTemplates);
    
        $viewResolver->register('blade', function () use ($bladeCompiler) {
            return new CompilerEngine($bladeCompiler);
        });
    
        $viewFinder = new FileViewFinder($filesystem, $pathsToTemplates);
        $viewFactory = new \Illuminate\View\Factory($viewResolver, $viewFinder, $eventDispatcher);
        $viewFactory->setContainer($app);
        Facade::setFacadeApplication($app);
        $app->instance(\Illuminate\Contracts\View\Factory::class, $viewFactory);
        $app->alias(
            \Illuminate\Contracts\View\Factory::class,
            (new class extends View {
                public static function getFacadeAccessor() { return parent::getFacadeAccessor(); }
            })::getFacadeAccessor()
        );
        $app->instance(BladeCompiler::class, $bladeCompiler);
        $app->alias(
            BladeCompiler::class,
            (new class extends Blade {
                public static function getFacadeAccessor() { return parent::getFacadeAccessor(); }
            })::getFacadeAccessor()
        );
        
        return $viewFactory;
    });
    
    /** @var \Illuminate\Mail\MailManager $mailManager */
    $mailManager = $container->make('mail.manager');
    
    // send an email message
    $mailManager->to('person@example.com')->send(
        (new ExampleMailable)
            ->from('person@example.com')
            ->subject('Hello world')
    );
    
    /** @var \Swift_Message $message */
    $message = $mailManager->getSwiftMailer()->getTransport()->messages()[0];
    
    $response->getBody()->write('<pre>');
    $response->getBody()->write($message->toString());
    $response->getBody()->write('</pre>');
    
    return $response;
});

class ExampleMailable extends Mailable
{
    public function build()
    {
        return $this->view('example');
    }
}

$app->run();
