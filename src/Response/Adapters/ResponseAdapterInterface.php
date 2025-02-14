<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing\Response\Adapters;

use Psr\Http\Message\ResponseInterface;

interface ResponseAdapterInterface
{
    /**
     * Convert a response to a PSR-7 HTTP message.
     *
     * @param  object $response The response object to convert.
     * @return ResponseInterface
     */
    public function convert(object $response): ResponseInterface;
}
