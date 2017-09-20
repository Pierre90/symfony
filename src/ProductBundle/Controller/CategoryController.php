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

class CategoryController extends Controller
{
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
       // $categories = $repository->findAll();
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