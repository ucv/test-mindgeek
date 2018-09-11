<?php
/**
 * Created by PhpStorm.
 * User: uvale
 * Date: 11-Sep-18
 * Time: 20:50
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/",name="homepage")
     */
    public function testImportAction()
    {
        return new Response('mama are mere');
    }
}