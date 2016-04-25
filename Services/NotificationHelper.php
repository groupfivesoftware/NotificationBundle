<?php

namespace GFS\NotificationBundle\Services;


use GFS\NotificationBundle\Entity\Notification;
use GFS\NotificationBundle\Notification\SocketIO;
use GFS\NotificationBundle\NotificationBundle;
use Symfony\Component\DependencyInjection\Container;

class NotificationHelper {

    private $container;

    public function __construct(Container $container){
        $this->container = $container;
    }

    public function createNotification($type = 'simple',$description = ''){
        $notification = new Notification();
        $notification->setType($type)
            ->setDescription($description);

        $this->sendNotification($notification);
    }

    public function sendNotification(Notification $notification){

        $socketio = new SocketIO();
        $host = $this->container->getParameter('gfs_notifications.config')['host'];
        $port = $this->container->getParameter('gfs_notifications.config')['port'];
        if($socketio->send($host,$port,json_encode([ 'notification' => $notification, 'token' => file_get_contents(NotificationBundle::getGfsNotificationToken())]),"ws://$host:$port/")){
            return true;
        }

        return false;
    }
} 