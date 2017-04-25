<?php
namespace UAPI\Model;

class Users extends \Phalcon\Mvc\Model //implements \Phalcon\ValidationInterface
{
    public $id;

    public $email;
    
    public $forename;
    
    public $surname;
    
    public $created;

    
    public function validation()
    {
        $validator = new \Phalcon\Validation();
        
        $validator->add(
            "forename",
            new \Phalcon\Validation\Validator\PresenceOf(
                [
                    "message" => "The forename is required",
                ]
            )
        );
        
        $validator->add(
            "surname",
            new \Phalcon\Validation\Validator\PresenceOf(
                [
                    "message" => "The surname is required",
                ]
            )
        );

        $validator->add(
            "email",
            new \Phalcon\Validation\Validator\PresenceOf(
                [
                    "message" => "The e-mail is required",
                ]
            )
        );

        // Email must be unique
        $validator->add(
            "email",
            new \Phalcon\Validation\Validator\Uniqueness(
                [
                    'message' => 'Email must be unique',
                ]
            )
        );

        return $this->validate($validator);
    }
}
