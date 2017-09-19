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
use AppBundle\Entity\Product;

class EmailUpdate implements EventSubscriber
{
    protected $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function getSubscribedEvents()
    {
        return array(
            'postPersist',
            'postUpdate',
        );
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->index($args);
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        //$this->index($args);
    }

    public function index(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if(!$entity instanceof Product)
        {
            return;
        }

        $mail = $entity->getUser()->getMail();
        $message = $this->mailer->createMessage("Product". $object->getName() ." a été modifié");
        $this->mailer->send($message, $mail);
    }
}