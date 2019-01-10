<?php

namespace BNS\OAuth\Provider;

use League\OAuth2\Client\Entity\User;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;

/**
 * @author Jérémie Augustin <jeremie.augustin@pixel-cookers.com>
 */
class BNSOAuthProvider extends AbstractProvider
{
    protected $domain = "https://test-auth.beneylu.com";

    public function setDomain($domain)
    {
        if (0 !== strpos($domain, 'http')) {
            $domain = 'https://' . $domain;
        }
        $this->domain = $domain;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function urlAuthorize()
    {
        return sprintf('%s/oauth/v2/authorize', $this->getDomain());
    }

    public function urlAccessToken()
    {
        return sprintf('%s/oauth/v2/token', $this->getDomain());
    }

    public function urlUserDetails(AccessToken $token)
    {
        return sprintf('%s/oauth/v2/users?access_token=%s', $this->getDomain(), $token->accessToken);
    }

    public function userDetails($response, AccessToken $token)
    {
        $keys = array('id', 'profile', 'classroom_ids', 'school_ids');

        $res = array();
        foreach ($keys as $key) {
            $res[$key] = isset($response->{$key})? $response->{$key} : null;
        }

        return $res;
    }


    //Début des routes personnalisées

    protected function fetchUserRoute(AccessToken $token, $url)
    {
        try {

            $client = $this->getHttpClient();
            $client->setBaseUrl($url);
            if ($this->headers) {
                $client->setDefaultOption('headers', $this->headers);
            }

            $request = $client->get()->send();
            $response = $request->getBody();

        } catch (BadResponseException $e) {
            // @codeCoverageIgnoreStart
            $raw_response = explode("\n", $e->getResponse());
            throw new IDPException(end($raw_response));
            // @codeCoverageIgnoreEnd
        }
        return $response;
    }

    public function urlUserFirstName(AccessToken $token)
    {
        return sprintf('%s/oauth/v2/routes/firstName?access_token=%s', $this->getDomain(), $token->accessToken);
    }

    public function getUserFirstName(AccessToken $token)
    {
        $response = $this->fetchUserRoute($token, $this->urlUserFirstName($token));

        return $this->userFirstName(json_decode($response), $token);
    }

    public function userFirstName($response, AccessToken $token)
    {
        $keys = array('id', 'first_name');
        $res = array();
        foreach ($keys as $key) {
            $res[$key] = isset($response->{$key})? $response->{$key} : null;
        }
        return $res;
    }

    public function urlUserOffers(AccessToken $token)
    {
        return sprintf('%s/oauth/v2/routes/offers?access_token=%s', $this->getDomain(), $token->accessToken);
    }

    public function getUserOffers(AccessToken $token)
    {
        $response = $this->fetchUserRoute($token, $this->urlUserOffers($token));
        return $this->userOffers(json_decode($response, true), $token);
    }

    public function userOffers($response, AccessToken $token)
    {
        return (array) $response;
    }

    public function urlUserClassrooms(AccessToken $token)
    {
        return sprintf('%s/oauth/v2/routes/classrooms?access_token=%s', $this->getDomain(), $token->accessToken);
    }

    public function getUserClassrooms(AccessToken $token)
    {
        $response = $this->fetchUserRoute($token, $this->urlUserClassrooms($token));
        return $this->userClassrooms(json_decode($response), $token);
    }

    public function userClassrooms($response, AccessToken $token)
    {
        $keys = array('id', 'classrooms');
        $res = array();
        foreach ($keys as $key) {
            $res[$key] = isset($response->{$key})? $response->{$key} : null;
        }
        return $res;
    }

    public function urlUserSchools(AccessToken $token)
    {
        return sprintf('%s/oauth/v2/routes/schools?access_token=%s', $this->getDomain(), $token->accessToken);
    }

    public function getUserSchools(AccessToken $token)
    {
        $response = $this->fetchUserRoute($token, $this->urlUserSchools($token));
        return $this->userSchools(json_decode($response), $token);
    }

    public function userSchools($response, AccessToken $token)
    {
        $keys = array('id', 'schools');
        $res = array();
        foreach ($keys as $key) {
            $res[$key] = isset($response->{$key})? $response->{$key} : null;
        }
        return $res;
    }

    public function urlUserSchoolUAIs(AccessToken $token)
    {
        return sprintf('%s/oauth/v2/routes/schoolUAIs?access_token=%s', $this->getDomain(), $token->accessToken);
    }

    public function getUserSchoolUAIs(AccessToken $token)
    {
        $response = $this->fetchUserRoute($token, $this->urlUserSchoolUAIs($token));
        return $this->userSchoolUAIs(json_decode($response), $token);
    }

    public function userSchoolUAIs($response, AccessToken $token)
    {
        $keys = array('id', 'school_uais');
        $res = array();
        foreach ($keys as $key) {
            $res[$key] = isset($response->{$key})? $response->{$key} : null;
        }
        return $res;
    }


}
