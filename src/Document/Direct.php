<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 23/04/2020
 * Time: 00:24
 */

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;


/**
 * @MongoDB\Document
 */
class Direct
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /** @MongoDB\ReferenceOne(targetDocument=User::class, inversedBy="directs", cascade={"persist"}) */
    private $user;

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
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }





}