<?php

namespace UAPI\Application;

class Micro
{
    /**
     * DI Keys for all registered objects/properties.
     */
    const DI_CONFIG = 'config';
    const DI_APPLICATION = 'application';
    const DI_DB = 'db';
    const DI_ROUTER = 'router';
    const DI_URL_HELPER = 'urlHelper';
    const DI_ERROR_HELPER = 'errorHelper';

    /**
     * Mvc app
     * @var \Phalcon\Mvc\Micro
     */
    protected $app;

    /**
     * Create an application and bootstrap the different sections.
     * @param Application configuration
     **/
    public function __construct($appConfig)
    {
        $this->bootstrapApplication()
             ->bootstrapDi($appConfig)
             ->bootstrapRouting()
             ->bootstrapErrorHandling();
    }

    /**
     * Bootstrap the micro application.
     * @return \UAPI\Application\Micro
     **/
    private function bootstrapApplication()
    {
        $this->app = new \Phalcon\Mvc\Micro();
        $this->app->setDI(new \Phalcon\DI\FactoryDefault);
        $this->app->setEventsManager(new \Phalcon\Events\Manager);
        return $this;
    }

    /**
     * Attach dependencies to the DI container.
     * All DI Objects are attached here. The DI is not modified
     * outside of this method.
     * @return \UAPI\Application\Micro
     **/
    private function bootstrapDi($appConfig)
    {
        $di = $this->getApplication()->getDI();

        //configuration
        $di->setShared(self::DI_CONFIG, $appConfig);

        //circular
        $di->setShared(
            self::DI_APPLICATION,
            function () {
                return $this;
            }
        );

        //Set the DB.
        $di->setShared(
            self::DI_DB,
            function () use ($appConfig) {
                return new \Phalcon\Db\Adapter\Pdo\Mysql($appConfig['db']);
            }
        );

        //Router
        $router = new \Phalcon\Mvc\Router();
        $router->setUriSource(\Phalcon\Mvc\Router::URI_SOURCE_SERVER_REQUEST_URI);
        $di->setShared(self::DI_ROUTER, $router);

        //Url Helper
        $di->setShared(self::DI_URL_HELPER, new \Phalcon\Mvc\Url());

        //Error Helper
        $di->setShared(self::DI_ERROR_HELPER, new \UAPI\Controller\IndexCtrl());

        return $this;
    }

    /**
     * Attach routes to the application.
     * @return \UAPI\Application\Micro
     **/
    private function bootstrapRouting()
    {
        $config = $this->getApplication()->getDI()->get(self::DI_CONFIG);
        $this->getApplication()->notFound(
            function () {
                $controller = new \UAPI\Controller\IndexCtrl();
                return $controller->notFound();
            }
        );

        if (isset($config['routesCollection'])) {
            foreach ($config['routesCollection'] as $routeCollection) {
                $collection = new \Phalcon\Mvc\Micro\Collection();
                $collection->setHandler($routeCollection['setHandler'], true);
                foreach ($routeCollection['routes'] as $type => $routes) {
                    foreach ($routes as $pattern => $callback) {
                        $collection->{$type}($pattern, $callback);
                    }
                }
                $this->getApplication()->mount($collection);
            }
        }

        //Set the correct url for the helper.
        if (isset($_SERVER['SERVER_NAME'])) {
            // Detect scheme
            $scheme = 'http';
            if (isset($_SERVER['HTTPS'])) {
                $scheme = 'https';
            }
            if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
                $scheme = $_SERVER['HTTP_X_FORWARDED_PROTO'];
            }
            $this->getApplication()
                    ->getDI()
                    ->get(self::DI_URL_HELPER)
                    ->setBaseUri($scheme . '://' . $_SERVER['SERVER_NAME']);
        }
        return $this;
    }

    /**
     * Register error handler for the whole application.
     * @return \UAPI\Application\Micro
     **/
    private function bootstrapErrorHandling()
    {
        $di = $this->getApplication()->getDI();
        set_exception_handler(
            function (\Exception $e) use ($di) {
                return $di->get(self::DI_ERROR_HELPER)
                    ->sendErrorResponse($e->getMessage(), 500);
            }
        );
        return $this;
    }

    /**
     * @return \Phalcon\Mvc\Micro
     **/
    public function getApplication()
    {
        return $this->app;
    }
}
