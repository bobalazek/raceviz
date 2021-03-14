<?php

namespace App\Exception\Controller;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class InvalidParameterException.
 */
class InvalidParameterException extends BadRequestHttpException
{
    public function __construct(string $message = 'Invalid parameter.')
    {
        parent::__construct($message);
    }
}
