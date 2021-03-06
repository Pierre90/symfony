<?php

namespace ProductBundle\Controller;

use ProductBundle\Entity\Note_product;
use ProductBundle\Entity\Product;
use ProductBundle\Entity\Bid;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ProductController extends Controller
{
    /**
     * Lists all product entities.
     *
     * @Route("/", name="product_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $error = "";
        if($request->query->get('error')=="delete"){
            $error = "Vous ne pouvez pas supprimer ce produit.";
        }
        else if($request->query->get('error')=="edit")
        {
            $error = "Vous ne pouvez pas modifier ce produit.";
        }
        $products = $em->getRepository('ProductBundle:Product')->findAll();
        //$categories = $em->getRepository('ProductBundle:Category')->findAll();
        $categories = $em->getRepository('ProductBundle:Category')->getCategoriesByParent();
        $users = $em->getRepository('UserBundle:User')->findAll();
        return $this->render('ProductBundle::product/index.html.twig', array(
            'products' => $products,
            'error' => $error,
            'categories'=>$categories,
            'users' => $users
        ));
    }

    /**
     * Creates a new product entity.
     *
     * @Route("/new", name="product_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */

    public function newAction(Request $request)
    {
        $product = new Product();

        $form = $this->createForm('ProductBundle\Form\ProductType', $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $product->getImage();

            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            // Move the file to the directory where images are stored
            $file->move(
                $this->getParameter('images_directory'),
                $fileName
            );

            // Update the 'image' property to store the image file name
            // instead of its contents
            $product->setImage($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);

            $em->flush();

            return $this->redirectToRoute('product_show', array('id' => $product->getId()));
        }

        return $this->render('ProductBundle::product/new.html.twig', array(
            'product' => $product,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a product entity.
     *
     * @Route("/{id}", name="product_show")
     * @Method("GET")
     */
    public function showAction(Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $views = $product->getViews();
        $product->setViews($views + 1);
        $em->persist($product);
        $em->flush();

        $deleteForm = $this->createDeleteForm($product);
        $repository = $this->getDoctrine()->getRepository(Note_product::class);
        $userNote = "";
        $bid = null;
        if($this->getUser())
        {
            $userNote = $repository->findOneBy(array("idProduct" => $product->getId(), "idUser" =>$this->getUser()->getId()));
        }
        $bid =$this->getDoctrine()->getRepository(Bid::class)->findOneBy( array('product' => $product->getId()));
        $notes = $product->getAllNotes($repository);
        $nbVotes = count($notes);
        $moy = $product->getMoyenne($notes);
        $buyer = $product->getBuyer();

        return $this->render('ProductBundle::product/show.html.twig', array(
            'product' => $product,
            'delete_form' => $deleteForm->createView(),
            'nbVotes' => $nbVotes,
            'userNote' => $userNote,
            'moyenne' => $moy,
            'bid'=>$bid,
            'buyer'=>$buyer
        ));
    }


    /**
     * Displays a form to edit an existing product entity.
     *
     * @Route("/{id}/edit", name="product_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function editAction(Request $request, Product $product)
    {
        if(!in_array('ROLE_ADMIN', $this->getUser()->getRoles()) && !in_array('ROLE_SUPER_ADMIN', $this->getUser()->getRoles()))
        {
            if($product->getUser() != $this->getUser())
            {
                return $this->redirectToRoute('product_index',array(
                    'error' => "edit"));

            }
        }
        $deleteForm = $this->createDeleteForm($product);
        $editForm = $this->createForm('ProductBundle\Form\ProductType', $product);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_edit', array('id' => $product->getId()));
        }

        return $this->render('ProductBundle::product/edit.html.twig', array(
            'product' => $product,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a product entity.
     *
     * @Route("/{id}", name="product_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteAction(Request $request, Product $product)
    {
        if($product->getUser() != $this->getUser())
        {
            return $this->redirectToRoute('product_index',array(
                'error' => "delete"));

        }
        $form = $this->createDeleteForm($product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(Note_product::class);
            $productNotes = $repository->findBy(array("idProduct"=>$product->getId()));
            foreach($productNotes as $productNote)
            {
                $em->remove($productNote);
            }
            $em->remove($product);
            $em->flush();
        }

        return $this->redirectToRoute('product_index');
    }

    /**
     * Creates a form to delete a product entity.
     *
     * @param Product $product The product entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Product $product)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('product_delete', array('id' => $product->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     *  @Route("/{id}/note", name="product_note")
     *  @Security("has_role('ROLE_USER')")
     */
    public function noteProductAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Note_product::class);

        $note = $repository->findOneBy(
            array('idUser' => $request->request->get('id_user'),
                    'idProduct' => $request->request->get('id_product'))
        );

        if(!$note)
        {
            $note = new Note_product();

            $note->setIdProduct($request->request->get('id_product'));
            $note->setIdUser($request->request->get('id_user'));
            $note->setNote($request->request->get('note'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($note);
            $em->flush();

            $repositoryProd =  $this->getDoctrine()->getRepository(Product::class);
            $product = $repositoryProd->find($request->request->get('id_product'));
            $notes = $product->getAllNotes($repository);
            $nbVotes = count($notes);
            $moy = $product->getMoyenne($notes);
            $arrData = ["moy"=>$moy, "nbVotes"=>$nbVotes, "note"=>$request->request->get('note')];

        }
        else
        {
            $arrData = ['error' => "Note déjà mise"];
        }

        return new JsonResponse($arrData);

    }

    /**
     *  @Route("/buy", name="buy_now")
     *  @Security("has_role('ROLE_USER')")
     *  @Method({"POST"})
     */
    public function buyNowAction(Request $request)
    {

        $id_product = $request->request->get('id_product');

        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository(Product::class)->find($id_product);
        $product->setBuyer($this->getUser());
       // $bids = $em->getRepository(Bid::class)->findAll();
        foreach($product->getBids() as $bid)
        {
            $em->remove($bid);
           
        }
        $em->persist($product);
        $em->flush();

        //Set le buyer et rajouter des if dans le show twig pour mettre vendu a la place du systeme d'enchère
        return new JSONResponse(array("buyer"=>$this->getUser()->getUserName()));
    }


}
