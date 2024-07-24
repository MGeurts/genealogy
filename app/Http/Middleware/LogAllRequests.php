<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogAllRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // -----------------------------------------------------------------------
        // LOG-VIEWER : log all requests
        // -----------------------------------------------------------------------
        $contents = json_decode($response->getContent(), true, 512);

        $headers = $request->header();

        $dt   = new Carbon;
        $data = [
            'path'         => $request->getPathInfo(),
            'method'       => $request->getMethod(),
            'ip'           => $request->ip(),
            'http_version' => $_SERVER['SERVER_PROTOCOL'] ?? null,
            'timestamp'    => $dt->toDateTimeString(),
            'headers'      => [
                // get all the required headers to log
                'user-agent' => $headers['user-agent'] ?? null,
                'referer'    => $headers['referer'] ?? null,
                'origin'     => $headers['origin'] ?? null,
            ],
        ];

        // if request if authenticated
        if ($request->user()) {
            $data['user'] = [
                'id'   => $request->user()->id,
                'name' => $request->user()->name,
            ];
        }

        // if you want to log all the request body
        if (count($request->all()) > 0) {
            // keys to skip like password or any sensitive information
            $hiddenKeys = ['password'];

            $data['request'] = $request->except($hiddenKeys);
        }

        // to log the message from the response
        if (! empty($contents['message'])) {
            $data['response']['message'] = $contents['message'];
        }

        // to log the errors from the response in case validation fails or other errors get thrown
        if (! empty($contents['errors'])) {
            $data['response']['errors'] = $contents['errors'];
        }

        // to log the data from the response, change the RESULT to your API key that holds data
        if (! empty($contents['result'])) {
            $data['response']['result'] = $contents['result'];
        }

        // a unique message to log, I prefer to save the path of request for easy debug
        $message = str_replace('/', '_', trim($request->getPathInfo(), '/'));

        // log the gathered information
        Log::debug($message, $data);

        return $response;
    }
}
