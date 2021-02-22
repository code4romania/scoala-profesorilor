<?php namespace Genuineq\Tms\Middleware;

use Config;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HostHeaderMiddleware {
    /**
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next) {

        $response = $next($request);

        // Only handle default responses (no redirects)
        if (!$this->isRelevant($request, $response)) {
            return $response;
        }

        $response->header('Host', Config::get('app.url', 'scoalaprofesorilor.ro'));

        return $response;
    }

    /**
     * Checks whether the response should be processed
     * by this middleware.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Http\Response $response
     *
     * @return bool
     */
    protected function isRelevant($request, $response) {
        // Only default responses, no redirects
        if (!$response instanceof Response) {
            return false;
        }

        return true;
    }
}
