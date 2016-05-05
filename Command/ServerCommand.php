<?php

namespace GFS\NotificationBundle\Command;


use GFS\NotificationBundle\NotificationBundle;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ServerCommand extends ContainerAwareCommand{

    protected function configure()
    {
        $this->setName('server:notification')
            ->setDescription('Application servers for websockets') ;
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $port = $this->getContainer()->getParameter('gfs_notifications.config')['port'];
        file_put_contents(NotificationBundle::getGfsNotificationToken(),md5(uniqid(rand(),true)));
        chmod(NotificationBundle::getGfsNotificationToken(),0777);
        $notification = $this->getContainer()->getParameter('gfs_notifications.config')['notification'];
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new $notification()
                )
            ),
            $port
        );

        $output->writeln('['.date('Y-m-d H:i:s').']Notification server has started.');
        $server->run();
    }
} 