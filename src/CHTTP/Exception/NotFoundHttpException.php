<?php

namespace Cresenity\Laravel\CHTTP\Exception;

/**
 * 404 HTTP Exception.
 *
 * @author Hery
 */
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException as SymfonyNotFoundHttpException;

class NotFoundHttpException extends SymfonyNotFoundHttpException
{
}
