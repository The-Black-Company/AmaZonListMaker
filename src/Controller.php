<?php

namespace App;

use App\AmazonProvider;
use App\AmazonResourceOwner;
use GuzzleHttp\Client;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;



class Controller
{
    private $view;
    
    public function __construct()
    {
        $this->view = new View();
    }
    
    public function handleRequest()
    {
        $uri = $_SERVER['REQUEST_URI'];
        
        switch ($uri) {
            case '/':
            case '/home':
                echo $this->view->render('home.html.twig');
                break;
            case '/product':
                echo $this->view->render('product.html.twig');
                break;
            case '/user':
                $this->handleAmazonLogin();
                break;
                case '/user_detail':
                    $userDetails = [];
                    if (isset($_GET['userDetails'])) {
                        $userDetails = json_decode($_GET['userDetails'], true);
                    }
                    echo $this->view->render('user_detail.html.twig', ['userDetails' => $userDetails]);
                    break;
                
            default:
                header("HTTP/1.0 404 Not Found");
                echo "Page not found";
                break;
        }
    }
    
    private function handleAmazonLogin()
    {
        // Replace these with your own Client ID, Client Secret, and Redirect URI
        $clientId = 'amzn1.application-oa2-client.de28d38518554f598306e3b213d4f5ef';
        $clientSecret = 'amzn1.oa2-cs.v1.b0dc1da062eb96f78e7eace0183b9b99cb05991dfd1775a427a81f0c9631ef3a';
        $redirectUri = 'http://localhost:8080/user_detail';
        
        $provider = new AmazonProvider([
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'redirectUri' => $redirectUri,
        ]);
        
        if (!isset($_GET['code'])) {
            $authUrl = $provider->getAuthorizationUrl();
            $_SESSION['oauth2state'] = $provider->getState();
            header('Location: ' . $authUrl);
            exit;
        }
        
        if (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
            unset($_SESSION['oauth2state']);
            exit('Invalid state');
        }
        
        try {
            $accessToken = $provider->getAccessToken('authorization_code', [
                'code' => $_GET['code'],
            ]);
            
            $resourceOwner = $provider->getResourceOwner($accessToken);
            $userDetails = $resourceOwner->toArray();
            
            // Redirect user to user_detail page with user details
            header('Location: http://localhost:8080/user_detail?userDetails=' . urlencode(json_encode($userDetails)));
            exit();
        } catch (IdentityProviderException $e) {
            exit($e->getMessage());
        }
    }
}
