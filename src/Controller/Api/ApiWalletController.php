<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 04/06/2020
 * Time: 12:13
 */

namespace App\Controller\Api;
use App\Service\UserService;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
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

}