<?php
/**
 * @author Jérémie Augustin <jeremie.augustin@pixel-cookers.com>
 */

require_once '../vendor/autoload.php';

////////////////
// Configuration
////////////////

/**
 * TODO : utiliser les identifiants qui vont ont été donnés. En TEST, les données ci dessous fonctionnent sur le scope read_full
 */

// Your OAuth client id
$oauth_client_id        = '2_123456';
// Your OAuth client secret
$oauth_client_secret    = 'SuperSecret';
// Your redirect uri where the user will be redirected after successfully authenticated
$oauth_redirect_uri     = 'http://oauth-test.dev/';

/**
 * TODO : vous devez ici décommenter la ligne correspond à vos travaux, TEST par défaut
 */

// PROD https://auth.beneyluschool.net
// TEST https://test-annuaire.beneylu.net
// DEV
$oauth_domain           = 'your_domain';

// set true only for development
$manualConnect          = true;
////////////////

/**
 * Le scope read_full fonctionne sur le client 2_123456
 * Il faut sinon utiliser le scope qui vous a été attribué
 */

$provider = new \BNS\OAuth\Provider\BnsOAuthProvider(array(
    'clientId'      => $oauth_client_id,
    'clientSecret'  => $oauth_client_secret,
    'redirectUri'   => $oauth_redirect_uri,
    'scopes'        => array('read_full'), //TODO  Placer ici le scope qui correspond à votre type de connexion : read_full, read_with_firstname, read_with_uai
));
$provider->setDomain($oauth_domain);

if (!isset($_GET['code'])) {
    // If we don't have an authorization code then get one

    if ($manualConnect) {
        // manual connect : show a link
        echo 'User not log in: ';
        printf('<a href="%s">connexion</a>', $provider->getAuthorizationUrl());
    } else {
        // auto connect
        // the normal way
        header('Location: '.$provider->getAuthorizationUrl());
        exit;
    }
} else {
    // the user came back from the OAuth server with a code

    try {
        // Try to get an access token (using the authorization code grant)
        $token = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code'],
            'grant_type' => 'authorization_code'
        ]);
        echo "OAuth Token:\n";
        // the oauth token
        var_dump($token);
        $expTime = $token->expires - date('U');

        echo 'Expiration (s) : ' . $expTime . '<br />';

        // We got an access token, let's now get the user's details
        $userDetails = $provider->getUserDetails($token);


        echo "User Details:\n";
        // Use these details to create/identify the user on your platform
        var_dump($userDetails);

        $userFirstName = $provider->getUserFirstName($token);
        echo "User FirstName:\n";
        // Use these details to create/identify the user on your platform
        var_dump($userFirstName);

        $userOffers = $provider->getUserOffers($token);
        echo "User Offers:\n";
        // Use these details to create/identify the user on your platform
        var_dump($userOffers);

        $userClassrooms = $provider->getUserClassrooms($token);
        echo "User Classrooms:\n";
        // Use these details to create/identify the user on your platform
        var_dump($userClassrooms);

        $userSchools = $provider->getUserSchools($token);
        echo "User Schools:\n";
        // Use these details to create/identify the user on your platform
        var_dump($userSchools);
        
        // Only for read_full and read_with_uai scope, 403 else
        $userSchoolUAIs = $provider->getUserSchoolUAIs($token);
        echo "User School UAIs:\n";
        // Use these details to create/identify the user on your platform
        var_dump($userSchoolUAIs);

        // TODO store user data in session and redirect the user to another url.

    } catch (Exception $e) {

        // Failed to get user details
        exit('something went wrong... ' . $e->getMessage());

    }

}
