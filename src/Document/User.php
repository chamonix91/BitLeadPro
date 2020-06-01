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
use Symfony\Component\HttpFoundation\File\File;


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
     * @MongoDB\Field(type="integer")
     */
    protected $level;

    /**
     * @MongoDB\Field(type="timestamp")
     */
    protected $birthday;

    /**
     * @MongoDB\Field(type="timestamp")
     */
    protected $created_date;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $tel;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $gender;

    /**
     * @MongoDB\Field(type="string")
     */
    private $photoName;

    /**
     * @MongoDB\ReferenceOne(targetDocument=Wallet::class)
     */
    public $wallet;


    /**
     * @MongoDB\ReferenceMany(targetDocument=Coupon::class,  mappedBy="owner",cascade={"persist"})
     */
    public $coupons;

    /**
     * @MongoDB\ReferenceMany(targetDocument=Transactions::class,  mappedBy="sender",cascade={"persist"})
     */
    public $transactions_sent;

    /**
     * @MongoDB\ReferenceMany(targetDocument=Transactions::class,  mappedBy="receiver",cascade={"persist"})
     */
    public $transactions_received;


    /** @MongoDB\ReferenceOne(targetDocument=User::class,  inversedBy="directs",cascade={"persist"}) */
    private $upline;

    /** @MongoDB\ReferenceMany(targetDocument=User::class, mappedBy="upline", cascade={"persist"}) */
    private $directs;

    public function __construct()
    {
        $this->directs = new \Doctrine\Common\Collections\ArrayCollection();

        $date = new \DateTime('now');

        //$this->created_date = strtotime(substr($date->getTimestamp(),0,24));
        $this->created_date = $date->getTimestamp();

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
    public function getCoupons()
    {
        return $this->coupons;
    }

    /**
     * @param mixed $coupons
     */
    public function setCoupons($coupons): void
    {
        $this->coupons = $coupons;
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
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param mixed $birthday
     */
    public function setBirthday($birthday): void
    {
        $this->birthday = $birthday;
    }

    /**
     * @return mixed
     */
    public function getCreatedDate()
    {
        return $this->created_date;
    }

    /**
     * @param mixed $created_date
     */
    public function setCreatedDate($created_date): void
    {
        $this->created_date = $created_date;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender): void
    {
        $this->gender = $gender;
    }

    /**
     * @return mixed
     */
    public function getPhotoName()
    {
        return $this->photoName;
    }

    /**
     * @param mixed $photoName
     */
    public function setPhotoName($photoName): void
    {
        $this->photoName = $photoName;
    }


    /**
     * @return mixed
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * @param mixed $tel
     */
    public function setTel($tel): void
    {
        $this->tel = $tel;
    }

    /**
     * @return mixed
     */
    public function getWallet()
    {
        return $this->wallet;
    }

    /**
     * @param mixed $wallet
     */
    public function setWallet($wallet): void
    {
        $this->wallet = $wallet;
    }










}