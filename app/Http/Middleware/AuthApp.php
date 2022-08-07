<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class AuthApp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return Response
     */
    public function handle(Request $request, Closure $next)
    {
        $device = $request->headers->get('Authorization');

        if (empty($device)) {
            return $this->errorJson(Response::HTTP_UNAUTHORIZED);
        }

        $user = User::query()
            ->firstOrCreate(
                [
                    'device' => $device
                ],
                [
                    'device' => $device
                ]
            );

        App::singleton('authed', static function () use ($user) {
            return $user;
        });

        return $next($request);
    }

    protected function errorJson($statusCode = Response::HTTP_BAD_REQUEST): Response
    {
        return new JsonResponse([
            'status' => false,
            'message' => 'Chưa truyền header có key là Authorization',
        ], $statusCode);
    }
}
