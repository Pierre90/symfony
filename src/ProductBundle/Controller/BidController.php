<?php
/**
 * Created by PhpStorm.
 * User: pierre
 * Date: 9/22/17
 * Time: 10:46 AM
 */

namespace ProductBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use ProductBundle\Entity\Bid;
use ProductBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BidController extends Controller
{
    /**
     *  @Route("/bid/new", name="new_bid")
     *  @Security("has_role('ROLE_USER')")
     *  @Method({"POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $new_bid = $request->request->get('bid');
        $id_user = $request->request->get('id_user');
        $id_product = $request->request->get('id_product');
        //  $arrResult = [array('id_user'=>$id_user, 'id_product'=>$id_product, 'bid'=>$new_bid)];
        $repository = $em->getRepository(Bid::class);
        $product = $em->getRepository(Product::class)->find($id_product);

        $bid_user = $repository->findOneBy(
            array('user' => $id_user,
                'product' => $id_product)
        );

        $highest_bid = $em->createQueryBuilder()
            ->select('MAX(b.bid)')
            ->from('ProductBundle:Bid', 'b')
            ->getQuery()
            ->getSingleScalarResult();



        if(isset($highest_bid) &&  $highest_bid >= $new_bid)
        {
            return new JsonResponse(array("error"=>"Veuillez renseigner une valeur supérieur à l'enchère actuelle"));
        }
        if($product->getMaximumBid() != null && $new_bid > $product->getMaximumBid())
        {
            //Alert si ok : article ahcheté.
            return new JsonResponse(array("buy"=>"ok"));
        }
        if(isset($bid_user))
        {
            $bid_user->setBid($new_bid);
            $em->persist($bid_user);
            $em->flush();

        }
        else
        {
            $bid_to_add = new Bid();
            $bid_to_add->setProduct($product);
            $bid_to_add->setBid($new_bid);
            $em->persist($bid_to_add);
            $em->flush();
        }

        return new JsonResponse(array('id_user'=>$id_user, 'id_product'=>$id_product, 'bid'=>$new_bid));
    }
}