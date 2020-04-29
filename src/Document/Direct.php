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
    private $upline;

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