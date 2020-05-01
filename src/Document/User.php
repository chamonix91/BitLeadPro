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


    /** @MongoDB\ReferenceOne(targetDocument=User::class,  inversedBy="directs",cascade={"persist"}) */
    private $upline;

    /** @MongoDB\ReferenceMany(targetDocument=User::class, mappedBy="upline", cascade={"persist"}) */
    private $directs;

    public function __construct()
    {
        $this->directs = new \Doctrine\Common\Collections\ArrayCollection();

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
     */
    public function setDirects($directs): void
    {
        $this->directs = $directs;
    }

    /**
     * @return mixed
     */
    public function getUpline()
    {
        return $this->upline;
    }

    /**
     * @param mixed $upline
     */
    public function setUpline($upline): void
    {
        $this->upline = $upline;
    }









}