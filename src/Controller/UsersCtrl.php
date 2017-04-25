<?php
namespace UAPI\Controller;

class UsersCtrl extends AbstractCtrl
{
    public function index()
    {
        $users = \UAPI\Model\Users::find();
        return $this->response([
            'status' => self::STATUS_OK,
            'users' => $users->toArray()
        ]);
    }
    
    public function get($id)
    {
        $user = \UAPI\Model\Users::findFirst($id);
        
        if ($user->id === null) {
            return $this->response(['status' => self::STATUS_NOT_FOUND]);
        }
        
        return $this->response([
            'status' => self::STATUS_OK,
            'user' => $user->toArray()
        ]);
    }
    
    public function insert()
    {
        $data = $this->request->getPost();

        $user = new \UAPI\Model\Users();
        $status = $user->save(
            [
                'email' => (!empty($data['email']) ? $data['email'] : ''),
                'forename' => (!empty($data['forename']) ? $data['forename'] : ''),
                'surname' => (!empty($data['surname']) ? $data['surname'] : '')
            ]
        );

        if ($status === true) {
            $user = \UAPI\Model\Users::findFirst($user->id);
            return $this->response(
                [
                    'status' => self::STATUS_OK,
                    'user' => $user->toArray()
                ],
                201,
                'Created'
            );
        } else {
            $errors = [];
            foreach ($user->getMessages() as $message) {
                $errors[] = $message->getMessage();
            }
            
            return $this->response(
                [
                    'status' => self::STATUS_ERROR,
                    'messages' => $errors
                ],
                409,
                'Conflict'
            );
        }
    }
    
    public function update($id)
    {
        $data = $this->request->getPut();

        $user = \UAPI\Model\Users::findFirst($id);
        if (!empty($data['email'])) {
            $user->email = $data['email'];
        }
        if (!empty($data['forename'])) {
            $user->forename = $data['forename'];
        }
        if (!empty($data['surname'])) {
            $user->surname = $data['surname'];
        }
        $status = $user->save();

        if ($status === true) {
            return $this->response(
                [
                    'status' => self::STATUS_OK,
                    'user' => $user->toArray()
                ],
                201,
                'Updated'
            );
        } else {
            $errors = [];
            foreach ($user->getMessages() as $message) {
                $errors[] = $message->getMessage();
            }
            
            return $this->response(
                [
                    'status' => self::STATUS_ERROR,
                    'messages' => $errors
                ],
                409,
                'Conflict'
            );
        }
    }
    
    public function delete($id)
    {
        $user = \UAPI\Model\Users::findFirst($id);
        if ($user->id === null) {
            return $this->response(['status' => self::STATUS_NOT_FOUND]);
        }

        if ($user->delete() === true) {
            return $this->response(
                [
                    'status' => self::STATUS_OK,
                ],
                201,
                'Deleted'
            );
        } else {
            $errors = [];
            foreach ($user->getMessages() as $message) {
                $errors[] = $message->getMessage();
            }
            
            return $this->response(
                [
                    'status' => self::STATUS_ERROR,
                    'messages' => $errors
                ],
                409,
                'Conflict'
            );
        }
    }
}
