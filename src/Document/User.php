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
    protected $firstname;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $lastname;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $address;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $postalcode;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $city;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $country;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $level;


    /**
     * @MongoDB\ReferenceMany(targetDocument=Coupon::class,  mappedBy="owner",cascade={"persist"})
     */
    public $coupons;


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

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address): void
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city): void
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country): void
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getPostalcode()
    {
        return $this->postalcode;
    }

    /**
     * @param mixed $postalcode
     */
    public function setPostalcode($postalcode): void
    {
        $this->postalcode = $postalcode;
    }











}