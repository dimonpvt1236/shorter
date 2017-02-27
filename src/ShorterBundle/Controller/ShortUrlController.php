<?php

namespace ShorterBundle\Controller;

use ShorterBundle\Entity\ShortUrl;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ShortUrlController extends Controller
{
    /**
     * @Route("/{shortUrl}", name="shorturl_redirect")
     * @param $shortUrl
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function redirectAction($shortUrl, Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('ShorterBundle:ShortUrl');
        $url = $repository->findOneBy(['shortUrl' => $shortUrl]);

        if ($url instanceof ShortUrl && $url->getShortUrl()) {
            $repository->increaseCountById($url->getId());

            return $this->redirect($url->getUrl());
        } else {
            return $this->render('@Shorter/shorturl/404.html.twig');
        }
    }

    /**
    * @Route("/", name="shorturl_home")
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function indexAction()
    {
        return $this->render('@Shorter/shorturl/home.html.twig');
    }
}
