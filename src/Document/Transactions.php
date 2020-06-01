<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 28/05/2020
 * Time: 15:35
 */

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Transactions
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $category;

    /**
     * @MongoDB\Field(type="integer")
     */
    protected $amount;

    /**
     * @MongoDB\Field(type="timestamp")
     */
    protected $trans_date;

    /**
     * @MongoDB\ReferenceOne(targetDocument=User::class,  inversedBy="$transactions_sent",cascade={"persist"})
     */
    protected $sender;

    /**
     * @MongoDB\ReferenceOne(targetDocument=User::class,  inversedBy="$transactions_received",cascade={"persist"})
     */
    protected $receiver;

    public function __construct()
    {
        $date = new \DateTime('now');
        $this->trans_date = $date->getTimestamp();

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
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category): void
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getTransDate()
    {
        return $this->trans_date;
    }

    /**
     * @param mixed $trans_date
     */
    public function setTransDate($trans_date): void
    {
        $this->trans_date = $trans_date;
    }

    /**
     * @return mixed
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param mixed $sender
     */
    public function setSender($sender): void
    {
        $this->sender = $sender;
    }

    /**
     * @return mixed
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * @param mixed $receiver
     */
    public function setReceiver($receiver): void
    {
        $this->receiver = $receiver;
    }






}