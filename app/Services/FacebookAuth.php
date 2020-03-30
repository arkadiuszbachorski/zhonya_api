<?php

namespace App\Services;

use GuzzleHttp\Client;

class FacebookAuth
{
    protected $client;

    protected $accessToken;

    protected function getContent($response) {
        return json_decode($response->getBody()->getContents());
    }

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://graph.facebook.com/'
        ]);
    }

    public function getAccessToken()
    {
        $response = $this->client->get('/oauth/access_token', [
            'query' => [
                'client_id' => env('FACEBOOK_CLIENT_ID'),
                'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
                'grant_type' => 'client_credentials',
            ]
        ]);

        if ($response->getStatusCode() === 200) {
            $this->accessToken = $this->getContent($response)->access_token;
        } else {
            abort(403);
        }
    }

    public function checkIfTokenIsValid($token)
    {
        if (!$this->accessToken) {
            $this->getAccessToken();
        }

        $response = $this->client->get('/debug_token', [
            'query' => [
                'input_token' => $token,
                'access_token' => $this->accessToken,
            ]
        ]);

        if ($response->getStatusCode() === 200) {
            return $this->getContent($response)->data->is_valid;
        } else {
            abort(403);
            return false;
        }

    }
}
