<?php

namespace App\Utils;


use Symfony\Component\HttpFoundation\Response;

class ErrorControl
{
    public function error($statusCode, $message) {
        $error = new Response();
        $error->setContent($message);
        $error->setStatusCode( (int) $statusCode);
        return $error;
    }
}