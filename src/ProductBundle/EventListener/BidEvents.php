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
use ProductBundle\Entity\Bid;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class BidEvents implements EventSubscriber
{

    protected $token;
    public function __construct(TokenStorage $token)
    {

        $this->token = $token;
    }

    public function getSubscribedEvents()
    {
        return array(

            'prePersist'
        );
    }


    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if(!$entity instanceof Bid)
        {
            return;
        }

        $entity->setUser($this->token->getToken()->getUser());
    }


}