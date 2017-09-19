<?php
/**
 * Created by PhpStorm.
 * User: pierre
 * Date: 9/18/17
 * Time: 2:33 PM
 */

namespace UserBundle\Controller;

use UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class UserController extends Controller
{
    /**
     * @Route("/", name="user_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('UserBundle:User')->findAll();

        return $this->render('UserBundle::user/index.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * @Route("/{id}/edit", name="user_edit")
     * @Method({"GET","POST"})
     */
    public function editAction(Request $request, User $user)
    {

        return $this->render('UserBundle::user/show.html.twig', array(
            'user' => $user,

        ));
    }

    /**
     * @Route("/{id}/show", name="user_show")
     * @Method({"GET"})
     */
    public function showAction(User $user)
    {
        return $this->render('UserBundle::user/show.html.twig', array(
            'user' => $user,

        ));
    }
}