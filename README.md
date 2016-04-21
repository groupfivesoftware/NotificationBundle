# 1. Installation
## 1. Install via composer
`composer require gfs/notifications ~v1.0`

## 2. add to AppKernel
```php
new GFS\NotificationBundle\NotificationBundle(),
```

# 2. Create Notification
```php
use GFS\NotificationBundle\Entity\Notification as Base;
class Notifications extends Base
{
    /**
    * @var int
    *
    * @ORM\Column(name="id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;

  /**
   * @var User
   *
   * @ORM\ManyToOne(targetEntity="User",inversedBy="notifications")
   * @ORM\JoinColumn(name="user_id", onDelete="cascade")
   */
  private $user;

  /**
   * @param UserInterface $user
   *
   * @return $this
   */
  public function setUser(UserInterface $user)
  {
      $this->user = $user;

      return $this;
  }

  /**
   * @return User
   */
  public function getUser()
  {
      return $this->user;
  }

  /**
   * Get id
   *
   * @return int
   */
  public function getId()
  {
      return $this->id;
  }

  /**
   * This is important because server will send your JSON notification ( json_encode(your notification) ).
   * You can custom to decide what field only want to go from server to your client.
   */
  public function jsonSerialize()
  {
      return [
          'type' => $this->getType(),
          'description' => $this->getDescription(),
          'checked' => $this->getChecked(),
          'checkedAt' => $this->getCheckedAt(),
          'createdAt' => $this->getCreateAt(),
          'url' => $this->url,
          'id' => $this->id,
          'userId' => $this->user->getUsername() //this help server to identify if specific user is connected and send only to that user, you can use athor field for common notification, example group notifications.
      ];
  }
}
```

Function jsonSerialize should return an array that must contain field 'userId', the server check all connections if contain this value.

# 3. Start server
Symfony 2 `php app/console server:notification`

Symfony 3 `php bin/console server:notification`

# 4. Client job
Use any websocket you want or any tehnologii. The most import thing is url, he must have GET paramter userId, that parameter will be bind to current connection.
This userId is used when you create a notification, the paramter `userId` come from function `jsonSerialize` are use to mach connection and send notification.

```javascript
var conn = new WebSocket('ws://yourip:8080?userId='+$scope.username);
conn.onopen = function(e) {
    console.log("Connection established!");
};

conn.onmessage = function(e) {
    var noitification = JSON.parse(e.data);
    // handle your notification object here
};
```

# 5. Create a notification
Just simple create an entity Notification. After success insert in database a notification will be send to server and server will find connection that match `userId` field from `jsonSerialize`.

```php
 $notification = new Notifications();
 $notification->setType('type')
     ->setDescription('Your Notification here')
     ->setUser($user)
 ;
 $this->get('doctrine.orm.default_entity_manager')->persist($notification);
 $this->get('doctrine.orm.default_entity_manager')->flush();
```
# Configuration
```yaml
#config.yml
gfs_notifications:
    host: localhost # ip or DNS where server run default is localhost
    port: 8080 # port when server want run default is 8080
```

# Default Entity field:
- id
- type ( string, 255 )
- description ( text )
- created_at ( DateTime )
- checked_at ( DateTime, default null )
- checked ( boolean )
- user ( instance of Symfony\Component\Security\Core\User\UserInterface )

Remeber if you want rewrite __contrusct don't forgot to write:

`$this->created_at = new \DateTime('now')`.