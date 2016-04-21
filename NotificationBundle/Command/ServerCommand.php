<?php
/**
 * Created by PhpStorm.
 * User: laurentiu
 * Date: 4/18/16
 * Time: 5:11 PM
 */

namespace GFS\NotificationBundle\Command;


use GFS\NotificationBundle\Notification\Notification;
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
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new Notification()
                )
            ),
            $port
        );

        $server->run();
    }
} 