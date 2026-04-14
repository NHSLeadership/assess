<?php

namespace App\Services;

use Auth0\SDK\Utility\HttpResponse;

class Auth0UserService
{
    public function getUserByUsername(string $username): ?array
    {
        $response = app('auth0')
            ->management()
            ->users()
            ->getAll([
                'q' => sprintf('username:"%s"', $username),
                'search_engine' => 'v3',
            ]);

        if (! HttpResponse::wasSuccessful($response)) {
            return null;
        }

        $users = HttpResponse::decodeContent($response);

        return $users[0] ?? null;
    }
}
