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
                'q' => sprintf('username:"%s"', $this->escapeAuth0SearchValue($username)),
                'search_engine' => 'v3',
            ]);

        if (! HttpResponse::wasSuccessful($response)) {
            return null;
        }

        $users = HttpResponse::decodeContent($response);

        return $users[0] ?? null;
    }
    private function escapeAuth0SearchValue(string $value): string
    {
        return str_replace(
            ['\\', '"'],
            ['\\\\', '\\"'],
            $value
        );
    }
}
