<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 13/04/2020
 * Time: 11:41
 */

namespace App\Document;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;


use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;


/**
 * @MongoDB\Document
 */

class User extends BaseUser
{

    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $codeUser;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $level;

    /**
     * @MongoDB\ReferenceOne(targetDocument=Partnership::class)
     */
    public $partnership;

    /** @MongoDB\ReferenceMany(targetDocument=Direct::class, mappedBy="upline", cascade={"persist"}) */
    private $directs;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->directs = new arrayCollection();
    }


    /**
     * @return mixed
     */
    public function getCodeUser()
    {
        return $this->codeUser;
    }

    /**
     * @param mixed $codeUser
     */
    public function setCodeUser($codeUser): void
    {
        $this->codeUser = $codeUser;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param mixed $level
     */
    public function setLevel($level): void
    {
        $this->level = $level;
    }

    /**
     * @return mixed
     */
    public function getPartnership()
    {
        return $this->partnership;
    }

    /**
     * @param mixed $partnership
     */
    public function setPartnership($partnership): void
    {
        $this->partnership = $partnership;
    }

    /**
     * @return mixed
     */
    public function getDirects()
    {
        return $this->directs;
    }

    /**
     * @param mixed $directs
     * @return User
     */
    public function addDirects(Direct $directs): self
    {
        if (!$this->directs->contains($directs)) {
            $this->directs[] = $directs;
            $directs->setUpline($this);
        }

        return $this;
    }



}