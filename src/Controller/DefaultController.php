<?php
/**
 * Created by PhpStorm.
 * User: uvale
 * Date: 11-Sep-18
 * Time: 20:50
 */

namespace App\Controller;

use App\Services\MgMovies;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    private $mgMovies;
    private $paginator;

    public function __construct(PaginatorInterface $paginator, MgMovies $mgMovies)
    {
        $this->mgMovies = $mgMovies;
        $this->paginator = $paginator;

    }

    /**
     * @Route("/",name="homepage")
     */
    public function viewMovieListAction(Request $request)
    {
        /** @var Paginator $paginator */
        $paginator = $this->get('knp_paginator');

        $movies = $this->mgMovies->getData();

        $pagination = $paginator->paginate(
            $movies,
            $request->query->getInt('page', 1),
            9
        );
        $pagination->setTotalItemCount(count($movies));

        return $this->render(
            'default/homepage.html.twig',[
                'movies' => $pagination
            ]
        );
    }

    /**
     * @Route("/movie/{id}/{slug}", name="movie")
     */
    public function viewMovieAction($id,$slug = ''){
        $movies = $this->mgMovies->getData();
        $movie = null;
        foreach ($movies as $movie){
            if($movie['id'] == $id){
                $movie = $movie;
                break;
            }
        }
        if(!$movie){
            throw new NotFoundHttpException('This movie is not available');
        }

        return $this->render(
            'default/movie.html.twig',[
                'movie' => $movie
            ]
        );
    }

    /**
     * @Route("/get-image", name="get-image")
     */
    public function getImageAction(Request $request, MgMovies $mgMovies){
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