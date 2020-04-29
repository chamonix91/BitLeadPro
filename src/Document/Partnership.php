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
class Partnership
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $codeUpline;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $codeDownline;

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
    public function getCodeUpline()
    {
        return $this->codeUpline;
    }

    /**
     * @param mixed $codeUpline
     */
    public function setCodeUpline($codeUpline): void
    {
        $this->codeUpline = $codeUpline;
    }

    /**
     * @return mixed
     */
    public function getCodeDownline()
    {
        return $this->codeDownline;
    }

    /**
     * @param mixed $codeDownline
     */
    public function setCodeDownline($codeDownline): void
    {
        $this->codeDownline = $codeDownline;
    }





}