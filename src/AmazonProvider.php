<?php

namespace App;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;

class AmazonProvider extends AbstractProvider
{
    protected $domain = 'https://www.amazon.com';

    public function getBaseAuthorizationUrl()
    {
        return $this->domain . '/ap/oa';
    }

    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->domain . '/ap/token';
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return $this->domain . '/user/profile?user_id=' . $token->getResourceOwnerId();
    }

    protected function getDefaultScopes()
    {
        return ['profile:user_id'];
    }

    protected function getScopeSeparator()
    {
        return ' ';
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
        if (isset($data['error'])) {
            throw new IdentityProviderException(
                $data['error_description'] ?? $response->getReasonPhrase(),
                $response->getStatusCode(),
                $response
            );
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new AmazonResourceOwner($response);
    }
}
