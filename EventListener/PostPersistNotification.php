<?php
namespace GFS\NotificationBundle\EventListener;


use Doctrine\ORM\Event\LifecycleEventArgs;
use GFS\NotificationBundle\Entity\Notification;
use GFS\NotificationBundle\Services\NotificationHelper;
use Symfony\Component\DependencyInjection\Container;

class PostPersistNotification {

    private $notificationHelper;

    public function __construct(NotificationHelper $helper){
        $this->notificationHelper = $helper;
    }

    public function postPersist(LifecycleEventArgs $args){
        $entity = $args->getEntity();

        if(!$entity instanceof Notification) return;
        // don't stop persist if fail to send server notification
        try{
            $this->notificationHelper->sendNotification($entity);
        }
        catch(\Exception $e){
            return;
        }

    }
}