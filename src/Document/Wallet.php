<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 28/05/2020
 * Time: 14:57
 */

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Wallet
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\Field(type="integer")
     */
    protected $income;

    /**
     * @MongoDB\Field(type="integer")
     */
    protected $expenses;

    /**
     * @MongoDB\Field(type="integer")
     */
    protected $balance;

    /**
     * Wallet constructor.
     */
    public function __construct()
    {
        $this->income = 0;
        $this->expenses = 0;
        $this->balance = 0;
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
    public function getIncome()
    {
        return $this->income;
    }

    /**
     * @param mixed $income
     */
    public function setIncome($income): void
    {
        $this->income = $income;
    }

    /**
     * @return mixed
     */
    public function getExpenses()
    {
        return $this->expenses;
    }

    /**
     * @param mixed $expenses
     */
    public function setExpenses($expenses): void
    {
        $this->expenses = $expenses;
    }

    /**
     * @return mixed
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param mixed $balance
     */
    public function setBalance($balance): void
    {
        $this->balance = $balance;
    }






}