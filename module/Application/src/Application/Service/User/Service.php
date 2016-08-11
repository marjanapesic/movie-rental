<?php

namespace Application\Service\User;

use Zend\Crypt\Password\Bcrypt;

class Service {

    /**
     * Static function for checking hashed password (as required by Doctrine)
     * @param  \Application\Entity\User $user       The identity object
     * @param  string         $passwordGiven Password provided by the user, to verify
     * @return boolean                       If the password was correct or not
     */
    public static function verifyHashedPassword(\Application\Entity\User $user, $passwordGiven)
    {
        $bcrypt = new Bcrypt();
        return $bcrypt->verify($passwordGiven, $user->getPasswordHash());
    }

    public function createPassword($password)
    {
        $bcrypt = new Bcrypt();
        return $bcrypt->create($password);
    }

}