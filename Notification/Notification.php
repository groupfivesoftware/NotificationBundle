<?php

namespace GFS\NotificationBundle\Notification;


use GFS\NotificationBundle\NotificationBundle;
use Guzzle\Http\QueryString;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class Notification implements MessageComponentInterface{

    protected $users = [];
    private $GFS_NOTIFICATION_TOKEN;

    public function __construct(){
        $this->clients = new \SplObjectStorage();
        $this->GFS_NOTIFICATION_TOKEN = file_get_contents(NotificationBundle::getGfsNotificationToken());
    }

    /**
     * When a new connection is opened it will be passed to this method
     * @param  ConnectionInterface $conn The socket/connection that just connected to your application
     * @throws \Exception
     */
    function onOpen(ConnectionInterface $conn)
    {
        /** @var QueryString $GET */
        $GET = $conn->WebSocket->request->getQuery();
        if(empty($this->users[$GET->get('userId')])) $this->users[$GET->get('userId')] = [];
        $this->users[$GET->get('userId')][] = $conn;

        echo "New connection! ({$conn->resourceId})".PHP_EOL;
    }

    /**
     * This is called before or after a socket is closed (depends on how it's closed).  SendMessage to $conn will not result in an error if it has already been closed.
     * @param  ConnectionInterface $conn The socket/connection that is closing/closed
     * @throws \Exception
     */
    function onClose(ConnectionInterface $conn)
    {
        foreach($this->users as $key => $val){
            foreach($val as $k => $v){
                if($conn === $v){
                    unset($this->users[$key][$k]);
                    if(empty($this->users[$key]))unset($this->users[$key]);
                }
            }
        }

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    /**
     * If there is an error with one of the sockets, or somewhere in the application where an Exception is thrown,
     * the Exception is sent back down the stack, handled by the Server and bubbled back up the application through this method
     * @param  ConnectionInterface $conn
     * @param  \Exception $e
     * @throws \Exception
     */
    function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

    /**
     * Triggered when a client sends data through the socket
     * @param  \Ratchet\ConnectionInterface $from The socket/connection that sent the message to your application
     * @param  string $msg The message received
     * @throws \Exception
     */
    function onMessage(ConnectionInterface $from, $msg)
    {
        $notification = json_decode($msg,true);

        if($notification['token'] == $this->GFS_NOTIFICATION_TOKEN){
            $notification = $notification['notification'];
            if(!empty($this->users[$notification['userId']])){
                foreach ($this->users[$notification['userId']] as $client) {
                    // The sender is not the receiver, send to each client connected
                    $client->send(json_encode($notification));
                }
            }
        }
    }
}