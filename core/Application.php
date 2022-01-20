<?php

namespace app\core;
use app\core\db\Database;

class Application
{
    public string $layout = 'main';
    public static string $ROOT_DIR;
    public string $userClass;
    public Router $router;
    public Request $request;
    public Response $response;
    public Session $session;
    public View $view;
    public Database $db;
    public static Application $app;
    public ?Controller $controller = null;
    public ?UserModel $user;

    public function __construct($rootPath,array $config)
    {
        $this->userClass =$config['userClass'];
        self::$ROOT_DIR=$rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->view = new View();
        $this->router = new Router($this->request,$this->response);
        $this->db = new Database($config['db']);

        $primaryValue = $this->session->get('user');
        if($primaryValue){
            $primaryKey = (new $this->userClass)->primaryKey();
            $this->user = (new $this->userClass)->findOne([$primaryKey => $primaryValue]);
        }else{
            $this->user = null;
        }

    }

    public static function isGuest(): bool
    {
        return !self::$app->user;
    }

    /**
     * @return Controller
     */
    public function getController(): Controller
    {
        return $this->controller;
    }

    /**
     * @param Controller $controller
     */
    public function setController(Controller $controller): void
    {
        $this->controller = $controller;
    }


    public function run()
    {
        try {
            echo $this->router->resolve();
        }catch (\Exception $exception){
            $this->response->setStatusCode($exception->getCode());
            echo $this->view->renderView('_error', [
                'exception' => $exception
            ]);
        }
    }

    public function login(UserModel $user): bool
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        $this->session->set('user',$primaryValue);
        return true;
    }

    public function logout()
    {
        $this->user = null;
        $this->session->remove('user');
    }
}