<?php

namespace GFS\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class Notification
 * @package NotificationBundle\Entity
 * @ORM\Entity()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="entity", type="string")
 */
class Notification implements \JsonSerializable{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(name="checked", type="boolean", options={"default": false })
     */
    private $checked = 0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="checked_at", type="datetime", nullable=true)
     */
    private $checkedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_at", type="datetime")
     */
    private $createAt;

    /**
     * @var User
     */
    private $user;

    public function __construct(){
        $this->createAt = new \DateTime('now');
        $this->user = new User('georgepopescu','GFS');
    }

    /**
     * @param boolean $checked
     * @return $this
     */
    public function setChecked($checked)
    {
        $this->checked = $checked;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getChecked()
    {
        return $this->checked;
    }

    /**
     * @param mixed $checkedAt
     * @return $this
     */
    public function setCheckedAt($checkedAt)
    {
        $this->checkedAt = $checkedAt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCheckedAt()
    {
        return $this->checkedAt;
    }

    /**
     * @param \DateTime $createAt
     * @return $this
     */
    public function setCreateAt($createAt)
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

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
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return [
            'type' => $this->type,
            'description' => $this->description,
            'checked' => $this->checked,
            'checkedAt' => $this->checkedAt,
            'createdAt' => $this->createAt,
            'id' => $this->id,
            'userId' => $this->user->getUsername()
        ];
    }
}