<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 04/06/2020
 * Time: 12:13
 */

namespace App\Controller\Api;
use App\Document\User;
use App\Service\CommissionService;
use App\Service\UserService;
use Doctrine\ODM\MongoDB\DocumentManager;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/wallet")
 */
class ApiWalletController extends FOSRestController
{

    /////////////////////////////////////////////////////////
    ///////////  GET AVAILABLE UPLINES FOR CACH  ////////////
    /////////////////////////////////////////////////////////

    /**
     * @Route("/getavailableuplines/{amount}", name="api__available_uplines", methods={"GET"})
     * @param $amount
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAvailableUplines($amount, UserService $userService)
    {
        $encoders = [new JsonEncoder()]; // If no need for XmlEncoder
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $user= $this->getUser();
        $uplines = array();

        $upline= $user->getUpline();

        while($upline){

            $uplineJson = $userService->GetOneUser($serializer,$upline);

                if($upline->getWallet()){
                $balance = $upline->getWallet()->getBalance();
                    if($balance >= $amount){
                    array_push($uplines, $uplineJson);
                    }
                }
            $upline= $upline->getUpline();
        }

        $statusCode = 200;

        $view = $this->view($uplines, $statusCode);
        return $this->handleView($view);
    }

    ////////////////////////////////////
    ///////////  SEND CAsH  ////////////
    ////////////////////////////////////

    /**
     * @Route("/sendcash/{ids}/{idr}", name="api__send_cash", methods={"POST"})
     * @param $ids
     * @param $idr
     * @param Request $request
     * @param DocumentManager $dm
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function SendCash($ids,$idr,Request $request, DocumentManager $dm)
    {
        $commissionservice = new CommissionService();

        $data = json_decode(
            $request->getContent(),
            true
        );

        $amount = $data['amount'];

        $receiver =$dm->getRepository(User::class)->find($idr);
        $sender =$dm->getRepository(User::class)->find($ids);

        if($sender->getWallet()->getBalance() < $amount){
            $statusCode = 500;

            $view = $this->view('Not enough balance', $statusCode);
            return $this->handleView($view);
        }

        $commissionservice->SendCashToWallet($receiver,$amount,'Income',$dm);
        $commissionservice->SendCashToWallet($sender,$amount,'Expense',$dm);

        $commissionservice->TransactionSendCash('SendTransactions',$sender,$receiver,$amount,$dm);
        $commissionservice->TransactionSendCash('ReceiveTransactions',$sender,$receiver,$amount,$dm);


        $statusCode = 200;

        $view = $this->view($sender->getUsername().' sends '. $amount . '$ to '. $receiver->getUsername(), $statusCode);
        return $this->handleView($view);
    }

    ////////////////////////////////////////
    ///////////  GET MY WALLET  ////////////
    ////////////////////////////////////////

    /**
     * @Route("/getmywallet", name="api_get_my_wallet", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getMyWallet()
    {
        $encoders = [new JsonEncoder()]; // If no need for XmlEncoder
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $user= $this->getUser();
        $wallet = $user->getWallet();

        $statusCode = 200;

        $view = $this->view($wallet, $statusCode);
        return $this->handleView($view);
    }

}