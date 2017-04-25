<?php
namespace UAPI\Controller;

class IndexCtrl extends AbstractCtrl
{
    public function index()
    {
        return $this->response(['status' => self::STATUS_UP]);
    }

    public function notFound()
    {
        return $this->response(['status' => self::STATUS_NOT_FOUND]);
    }
    
    public function sendErrorResponse($message, $code)
    {
        $response = $this->response([
            'status' => self::STATUS_ERROR,
            'error' => $message
        ], $code);
        if (!$response->isSent()) {
            $response->send();
        }
        return false;
    }
}
