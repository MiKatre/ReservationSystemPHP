<?php

namespace App\Utils;


use Symfony\Component\HttpFoundation\Response;

/**
 * Class ErrorControl
 * @package App\Utils
 */
class ErrorControl
{
    /**
     * @param $statusCode
     * @param $message
     * @return Response
     */
    public function error($statusCode, $message) {
        $error = new Response();
        $error->setContent($message);
        $error->setStatusCode( (int) $statusCode);
        return $error;
    }
}