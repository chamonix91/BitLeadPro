<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 29/05/2020
 * Time: 15:54
 */

namespace App\Service;


use App\Document\Transactions;
use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;

class CommissionService
{
    /////////////////////////////
    /////  ADD COMMISSION ///////
    /////////////////////////////

    /**
     * @param UserInterface $user
     * @param int $amount
     * @param String $type
     * @param DocumentManager $dm
     * @return void
     */
    public function UpdateWallet(UserInterface $user, int $amount, String $type, DocumentManager $dm): void
    {
        $wallet = $user->getWallet();

        if ($type == 'Income'){

            $income = $wallet->getIncome() + $amount;
            $balance = $wallet->getBalance();
            $wallet->setIncome($income);
            $wallet->setBalance($balance + $amount);

        }

        if ($type == 'Expense'){

            $expense = $wallet->getExpenses() - $amount;
            $balance = $wallet->getBalance();
            $wallet->setExpenses($expense);
            $wallet->setBalance($balance - $amount);

        }

        $dm->persist($wallet);
        $dm->flush();

    }



    ////////////////////////////////
    ///// TRANSACTION UPGRADE  /////
    ////////////////////////////////

    /**
     * @param String $category
     * @param UserInterface $receiver
     * @param int $amount
     * @param DocumentManager $dm
     */
    public function TransactionUpgrade(String $category, UserInterface $receiver, int $amount, DocumentManager $dm )
    {
        $transaction = new Transactions();
        $transaction->setCategory($category);
        $transaction->setAmount($amount);
        $transaction->setReceiver($receiver);

        $dm->persist($transaction);
        $dm->flush();

    }

    /////////////////////////////////////////
    ///// CALCULATE UPGRADE COMMISSION  /////
    /////////////////////////////////////////

    /**
     * @param User $upline
     * @param int $j
     */
    public function CalculateCommission(User $user, int $j )
    {
        $ArrayCommission = [25,75,150,250,500,1500,2500];
        for ($i=0; $i<$j; $i++){
            $upline = $user->getUpline();

            if($upline->getLevel() >= 1){
                $amount = $ArrayCommission[$i];
                $this->AddCommission($upline,$amount,$dm);
                $this->TransactionUpgrade("Upgrade",$upline,$amount,$dm);
                $upline = $upline->getUpline();
                if (!$upline){
                    break;
                }
            }
        }

    }





}