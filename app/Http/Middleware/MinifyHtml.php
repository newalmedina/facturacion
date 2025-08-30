<?php

namespace App\Http\Middleware;

use Closure;
use voku\helper\HtmlMin;


class MinifyHtml
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($response->headers->get('Content-Type') === 'text/html; charset=UTF-8') {
            $htmlMin = new HtmlMin();
            $response->setContent($htmlMin->minify($response->getContent()));
        }

        return $response;
    }
}
