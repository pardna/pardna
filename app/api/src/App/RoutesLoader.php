<?php
namespace App;
use Silex\Application;
class RoutesLoader
{
    private $app;
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->instantiateControllers();
    }
    private function instantiateControllers()
    {
        $this->app['users.controller'] = $this->app->share(function () {
            $controller = new Controllers\UsersController($this->app['users.service']);
            $controller->setNotificationService($this->app['notification.service']);
            $controller->setApp($this->app);
            if($this->app['security.token_storage']->getToken()->getUser()) {
              //var_dump($this->app['security.token_storage']->getToken()->getUser());
            //  exit;
              $controller->setUser($this->app['security.token_storage']->getToken()->getUser());
            }
            // var_dump($this->app['security.token_storage']->getToken()->getTokenContext());
            // exit;
            return $controller;
        });


    }
    public function bindRoutesToControllers()
    {
        $api = $this->app["controllers_factory"];
        $api->get('/user/{id}', "users.controller:get");
        $api->post('/signup', "users.controller:signup");
        $api->post('/login', "users.controller:login");
        $api->post('/refresh-token', "users.controller:refreshToken");
        $api->post('/signin', "users.controller:signin");
        $api->post('/user/change-password', "users.controller:changePassword");
        $api->put('/user/{id}', "users.controller:update");
        $api->delete('/user/{id}', "users.controller:delete");
        $api->post('/user/send-code', "users.controller:sendCode");
        $api->post('/user/verify', "users.controller:verify");
        $api->post('/user/notifications', "users.controller:notifications");

        $this->app->mount($this->app["api.endpoint"].'/'.$this->app["api.version"], $api);
    }
}
