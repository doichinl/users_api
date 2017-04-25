<?php
namespace UAPI\Controller;

use Phalcon\Http\Response;

class AbstractCtrl extends \Phalcon\Mvc\Controller
{
    const STATUS_UP = 'UP';
    const STATUS_NOT_FOUND = 'NOT-FOUND';
    const STATUS_OK = 'OK';
    const STATUS_ERROR = 'ERROR';
    
    /**
     * Sets json response
     * @param array $data
     * @return Phalcon\Http\Response
     */
    protected function response($data, $code= 200, $message = '')
    {
        $response = new Response();
        $response
            ->setContentType('application/json')
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE')
            ->setStatusCode($code, $message)
            ->setJsonContent($data);
        return $response;
    }
}
