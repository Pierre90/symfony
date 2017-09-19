<?php

namespace UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class UserBundle extends Bundle
{

    /**** Permet de surcharger tout FOSUser BUndle et surtout les controllers" *****/
    public function getParent(){

        return "FOSUserBundle";
    }
}
