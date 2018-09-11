<?php
/**
 * Created by PhpStorm.
 * User: uvale
 * Date: 11-Sep-18
 * Time: 20:50
 */

namespace App\Controller;

use App\Services\MgMovies;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/",name="homepage")
     */
    public function testImportAction(MgMovies $mgMovies)
    {
        return $this->render(
            'default/_empty.html.twig'
        );
    }

    /**
     * @Route("/get-image", name="get-image")
     */
    public function getImage(Request $request, MgMovies $mgMovies){
        $backupImage = $this->getParameter('public_dir').'/assets/image-not-found.png';
        $url = $request->query->get('url');
        $res = $mgMovies->getResource($url);

        if($res->getStatusCode() == 200){
            $response = new Response();
            $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_INLINE, basename($url));
            $response->headers->set('Content-Disposition', $disposition);
            $response->headers->set('Content-Type', $res->getHeader('Content-Type'));
            $response->setContent($res->getBody()->getContents());
        }
        else{
            return $this->file($backupImage,null,ResponseHeaderBag::DISPOSITION_INLINE);
        }

        return $response;
    }
}