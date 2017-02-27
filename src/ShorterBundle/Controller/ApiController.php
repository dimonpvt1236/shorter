<?php

namespace ShorterBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use ShorterBundle\Entity\ShortUrl;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ApiController extends Controller
{

    /**
     * @Route("/shorturl/create", name="shorturl_create")
     * @Method({"POST"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createUrlAction(Request $request)
    {
        $serializer = $this->container->get('jms_serializer');

        if (!$request->get('url', false)) {
            return new Response($serializer->serialize(['errors' => ['url' => 'Link is required filed.']], 'json'), 400);
        }

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $request->get('url', false));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

            curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);

            if ($status != 200) {
                return new Response($serializer->serialize(['errors' => ['url' => 'Link is broken.']], 'json'), 400);
            }
        } catch(Exception $error) {
            return new Response($serializer->serialize(['errors' => ['url' => 'Link is broken.']], 'json'), 400);
        }

        try {
            $url = new ShortUrl();
            $url->setUrl($request->get('url'));

            $repository = $this->getDoctrine()->getRepository('ShorterBundle:ShortUrl');

            if ($shortUrl = $request->get('short_url', false)) {
                $exist = $repository->findOneBy(['shortUrl' => $shortUrl]);
                if ($exist instanceof ShortUrl) {
                    return new Response($serializer->serialize(['errors' => ['short_url' => 'Short link already exist.']], 'json'), 400);
                }

                $url->setShortUrl($shortUrl);
            } else {
                $i = 0;
                do {
                    $url->setShortUrl();
                    $exist = $repository->findOneBy(['shortUrl' => $url->getShortUrl()]);

                    if (++$i > 3) {
                        return new Response($serializer->serialize(['errors' => ['message' => 'Error save link. Please try again.']], 'json'), 400);
                    }
                } while($exist instanceof ShortUrl);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($url);
            $em->flush();

            if ($url->getId()) {
                $resultUrl = $this->generateUrl(
                    'shorturl_redirect',
                    ['shortUrl' => $url->getShortUrl()],
                    UrlGeneratorInterface::ABSOLUTE_URL
                );

                return new Response($serializer->serialize(['short_url' => $resultUrl], 'json'), 200);
            } else {
                return new Response($serializer->serialize(['errors' => ['message' => 'Error save link. Please try again.']], 'json'), 400);
            }
        } catch(\Exception $error) {
            return new Response($serializer->serialize(['errors' => ['message' => 'Error save link. Please try again.']], 'json'), 400);
        }
    }
}
