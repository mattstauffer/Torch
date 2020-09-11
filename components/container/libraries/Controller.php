<?php

namespace Acme;

use Acme\Database;
use Acme\Template;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Controller
{
    private $database;
    private $template;

    public function __construct(Database $database, Template $template)
    {
        $this->database = $database;
        $this->template = $template;
    }

    public function home(Request $request, Response $response)
    {
        $response->getBody()->write($this->template->render('home'));

        return $response;
    }
}
