<?php
namespace App\Controllers;

use Faber\Core\Controllers\Controller;
use Faber\Core\Response\Response;

class HomeController extends Controller
{
    protected Response $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }
    public function index(): void
    {
        $this->response->view('v1.home', [
            'h1' => 'Home page',
        ]);
    }
}