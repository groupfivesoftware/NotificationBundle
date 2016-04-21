<?php
/**
 * Created by PhpStorm.
 * User: laurentiu
 * Date: 4/18/16
 * Time: 5:17 PM
 */

namespace GFS\NotificationBundle\Services;


use NotificationBundle\Entity\Notification;
use NotificationBundle\Notification\SocketIO;
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
        if($socketio->send($host,$port,json_encode($notification),"ws://$host:$port/")){
            return true;
        }

        return false;
    }
} 