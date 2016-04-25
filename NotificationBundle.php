<?php

namespace GFS\NotificationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class NotificationBundle extends Bundle
{
    public static function  getGfsNotificationToken(){
        return __DIR__ . DIRECTORY_SEPARATOR . '.GFS_NOTIFICATION_TOKEN';
    }
}
