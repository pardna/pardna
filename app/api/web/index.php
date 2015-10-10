<?php

require_once __DIR__.'/../vendor/autoload.php';
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\InMemoryUserProvider;
use Symfony\Component\Security\Core\User\User;

$app = new Silex\Application();
require __DIR__ . '/../resources/config/prod.php';
require __DIR__ . '/../src/app.php';



$app->post('/api/v1/loginhjj', function(Request $request) use ($app){
  //  $vars = json_decode($request->getContent(), true);
    $vars = $request->request->all();
    try {
        if (empty($vars['username']) || empty($vars['password'])) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $vars['_username']));
        }

        /**
         * @var $user User
         */
        $user = $app['users']->loadUserByUsername($vars['username']);
        if (!$app['users']->authenticate($vars['username'], $vars['password'])) {
          $response = [
              'success' => false,
              'error' => 'Invalid credentials',
              'user' => $vars
          ];
        } else {
            $response = [
                'success' => true,
                'token' => $app['security.jwt.encoder']->encode([
                  'fullname' => $user->getFullName(),
                  "name" => $user->getEmail(),
                  "email" => $user->getEmail(),
                  "id" => $user->getid(),
                  "mobile" => $user->getMobile(),
                  "verified" => $user->getVerified()
                  ]),
            ];
        }
    } catch (UsernameNotFoundException $e) {
        $response = [
            'success' => false,
            'error' => 'Invalid credentials',
          //  'user' => $vars
        ];
    }

    return $app->json($response, ($response['success'] == true ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST));
});



$app['http_cache']->run();
