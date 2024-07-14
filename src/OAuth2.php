<?php

namespace App;

use Google\Client as GoogleClient;
use Google\Service\Oauth2 as GoogleOauth2;

class OAuth2
{
    private $client;

    public function __construct()
    {
        $this->client = new GoogleClient();
        $this->client->setClientId($_ENV['OAUTH2_CLIENT_ID']);
        $this->client->setClientSecret($_ENV['OAUTH2_CLIENT_SECRET']);
        $this->client->setRedirectUri($_ENV['OAUTH2_REDIRECT_URI']);
        $this->client->addScope(GoogleOauth2::USERINFO_EMAIL);
        $this->client->addScope(GoogleOauth2::USERINFO_PROFILE);
    }

    public function getAuthUrl()
    {
        return $this->client->createAuthUrl();
    }

    public function authenticate($code)
    {
        $this->client->fetchAccessTokenWithAuthCode($code);
        $token = $this->client->getAccessToken();

        if ($token && isset($token['access_token'])) {
            // Store token in session
            session_start();
            $_SESSION['oauth_token'] = $token['access_token'];
            $oauth2 = new GoogleOauth2($this->client);
            $userInfo = $oauth2->userinfo->get();
            return [
                'access_token' => $token['access_token'],
                'user_info' => $userInfo
            ];
        }

        return false;
    }

    public function isAuthenticated()
    {
        session_start();

        if (isset($_SESSION['oauth_token'])) {
            // Verify token
            $this->client->setAccessToken($_SESSION['oauth_token']);
            $oauth2 = new GoogleOauth2($this->client);

            try {
                $userInfo = $oauth2->userinfo->get();
                return $userInfo ? true : false;
            } catch (\Exception $e) {
                return false;
            }
        }

        return false;
    }

    public function logout()
    {
        session_start();
        unset($_SESSION['oauth_token']);
        session_destroy();
    }
}
