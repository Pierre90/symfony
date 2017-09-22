<?php
/**
 * Created by PhpStorm.
 * User: pierre
 * Date: 9/20/17
 * Time: 3:57 PM
 */

namespace ProductBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ProductBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class CategoryController extends Controller
{
    /**
     * Creates a new category entity.
     *
     * @Route("/category/new", name="add_category")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */

    public function newAction(Request $request)
    {
        $category = new Category();

        $form = $this->createForm('ProductBundle\Form\CategoryType', $category);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('ProductBundle::category/new.html.twig', array(
            'category' => $category,
            'form' => $form->createView(),
        ));
    }
    /**
     *  @Route("/category/all", name="category_search")
     *  @Security("has_role('ROLE_USER')")
     */
    public function searchCat(Request $request)
    {
        $toSearch = $request->query->get('q');
        $repository =  $this->getDoctrine()->getRepository(Category::class);

        $categories = $repository->createQueryBuilder('c')
            ->where('c.name LIKE :name')
            ->setParameter('name', '%'.$toSearch.'%')
            ->getQuery()
            ->getResult();

        $arrResult = [];
        $i=0;
        foreach($categories as $cat)
        {
            $arrResult[$i] = array("id"=>$cat->getId(), "text"=>$cat->getName());
            $i++;
        }
        return new JsonResponse($arrResult);
    }


}