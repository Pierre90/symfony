<?php

namespace UserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseController;

class SecurityController extends BaseController {
    public function loginAction(\Symfony\Component\HttpFoundation\Request $request){
        $response = parent::loginAction($request);

        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('product_index');
        }

        return $response;
    }
}