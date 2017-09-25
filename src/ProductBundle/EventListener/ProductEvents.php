<?php
/**
 * Created by PhpStorm.
 * User: pierre
 * Date: 9/19/17
 * Time: 11:15 AM
 */
namespace ProductBundle\EventListener;


use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use ProductBundle\Entity\Product;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ProductEvents implements EventSubscriber
{
    protected $mailer;
    protected $token;

    public function __construct(\Swift_Mailer $mailer, TokenStorage $token)
    {
        $this->mailer = $mailer;
        $this->token = $token;
    }

    public function getSubscribedEvents()
    {
        return array(

            'postUpdate',
            'prePersist'
        );
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->index($args);
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if(!$entity instanceof Product)
        {
            return;
        }

        if($entity->getEndDate()==null)
        {
            $entity->setEndDate(new \DateTime('+1 month'));
        }
        $entity->setViews(0);
        $entity->setUser($this->token->getToken()->getUser());
    }

    public function index(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if(!$entity instanceof Product)
        {
            return;
        }

        $mail = $entity->getUser()->getEmail();
        $message = new \Swift_Message("Produit modifié");
        $message->setBody("Product ". $entity->getTitle() ." a été modifié");
        $this->mailer->send($message, $mail);
    }
}