<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 29/04/2020
 * Time: 00:29
 */

namespace App\EventListener;


use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\Response;

class CookiesListener
{

    public function onKernelRequest(RequestEvent $event)
    {
        // You get the exception object from the received event
        $response = new Response();
        $timestamp = time() + 30 * 86400;
        $response->headers->setCookie(new Cookie('aff_id', 'aaaaaa', $timestamp));
        $response->send();

        $event->setResponse($response);

    }

}