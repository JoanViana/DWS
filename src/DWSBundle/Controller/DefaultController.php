<?php

namespace DWSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DWSBundle::index.html.twig');
    }
    
    public function adminAction()
    {
        return new Response('<html><body>Admin page!</body></html>');
    }
}
