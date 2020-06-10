<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 13/04/2020
 * Time: 13:16
 */

namespace App\Controller\Api;

use App\Document\Transactions;
use App\Document\User;

use App\Service\CommissionService;
use App\Service\FileUploader;
use App\Service\MlmService;
use App\Service\UserService;
use Doctrine\ODM\MongoDB\DocumentManager;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\UserBundle\Model\UserManagerInterface;
use function GuzzleHttp\Promise\all;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

use FOS\RestBundle\Controller\Annotations as Rest;


use JMS\Serializer\SerializationContext;
/**
 * @Route("/user")
 */
class ApiUserController extends FOSRestController
{

    //////////////////////////////////////////////
    ///////////  GET CURRENT USER  ///////////////
    //////////////////////////////////////////////

    /**
     * @Route("/current", name="api__logged_user", methods={"GET"})
     * @param Request $request
     * @param UserManagerInterface $userManager
     * @param UserService $userservice
     * @return Response
     */
    public function getcurrenttUser(Request $request, UserManagerInterface $userManager, UserService $userservice)
    {

        $encoders = [new JsonEncoder()]; // If no need for XmlEncoder
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $currentuser = $this->getUser();

        $user = $userservice->GetOneUser( $serializer, $currentuser);

        $statusCode = 200;

        $view = $this->view($user, $statusCode);
        return $this->handleView($view);
    }


    //////////////////////////////////////////////
    ///////////  MY ALL DIRECTS    ///////////////
    /// //////////////////////////////////////////

    /**
     * @Route("/myalldirects", name="api_user_mysirects", methods={"GET"})
     * @param Request $request
     * @param DocumentManager $dm
     * @param UserManagerInterface $userManager
     * @param UserService $userservice
     * @return JsonResponse
     */
    public function getmyalldirectsUser(DocumentManager  $dm, UserManagerInterface $userManager, UserService $userservice)
    {

        $encoders = [new JsonEncoder()]; // If no need for XmlEncoder
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $user = $this->getUser();
        $directs = $user->getDirects();

        if ($directs == null){
            return new JsonResponse(["No Directs Yet"], 500);

        }
        $alldirects = $userservice->GetManyUsers( $serializer,$directs);


        return new Response($alldirects, 200, ['Content-Type' => 'application/json']);
    }

    ////////////////////////////////////////////////
    ///////////  MY ALL INDIRECTS    ///////////////
    /// ////////////////////////////////////////////

    /**
     * @Route("/myallindirects", name="api_user_myindirects", methods={"GET"})
     * @param DocumentManager $dm
     * @param MlmService $mlmservice
     * @return JsonResponse
     */
    public function getmyallindirectsUser(DocumentManager  $dm, MlmService $mlmservice)
    {

        $encoders = [new JsonEncoder()]; // If no need for XmlEncoder
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $user = $this->getUser();
        $numberdirect= $this->getmydirectsnumber();

        $directs = $user->getDirects();

        if ($directs == null){
            return new JsonResponse(["No Directs Yet"], 500);

        }
        $allindirects = $mlmservice->GetDownlines( $serializer,$directs);

        $indirects = json_decode($allindirects,true);

        //$array = \array_diff($indirects, $directs);




        return new Response($indirects, 200, ['Content-Type' => 'application/json']);
    }


    ////////////////////////////////////////////////
    ///////////  MY ALL INDIRECTS NUMBER   /////////
    /// ////////////////////////////////////////////

    /**
     * @Route("/myallindirectsnumber", name="api_user_myindirects_number", methods={"GET"})
     * @param DocumentManager $dm
     * @param MlmService $mlmservice
     * @return JsonResponse
     * @Rest\View()
     */
    public function getmyallindirectsNumber(DocumentManager  $dm, MlmService $mlmservice)
    {

        $encoders = [new JsonEncoder()]; // If no need for XmlEncoder
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $user = $this->getUser();
        $numberdirect= $this->getmydirectsnumber();

        $directs = $user->getDirects();

        if ($directs == null){
            return new JsonResponse(["No Directs Yet"], 500);

        }
        $allindirects = $mlmservice->GetDownlines( $serializer,$directs);

        $indirects = json_decode($allindirects,true);

        $numberdownlines = count($indirects);

        //dump($numberdirect);dump($numberdownlines);
        dump($indirects);
        die();

        $numberindirects = $numberdownlines - $numberdirect ;

        $statusCode = 200;
        $view = $this->view($numberindirects, $statusCode);
        return $this->handleView($view);




    }

    /////////////////////////////////////////////////////
    ///////////  MY ALL DIRECTS BY USER   ///////////////
    /// /////////////////////////////////////////////////

    /**
     * @Route("/alldirects/{id}", name="api_user_directs_by_id", methods={"GET"})
     * @param Request $request
     * @param DocumentManager $dm
     * @param UserManagerInterface $userManager
     * @param UserService $userservice
     * @return JsonResponse
     */
    public function getalldirectsByUser(DocumentManager  $dm, UserManagerInterface $userManager, UserService $userservice, $id)
    {

        $encoders = [new JsonEncoder()]; // If no need for XmlEncoder
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $user = $dm->getRepository(User::class)->find($id);

        $directs = $user->getDirects();

        if (!$directs){
            return new JsonResponse(["No Directs Yet"], 500);
        }
        $alldirects = $userservice->GetManyUsers( $serializer,$directs);

        return new Response($alldirects, 200, ['Content-Type' => 'application/json']);
    }


    //////////////////////////////////////////////
    ///////////  DIRECTS NUMBER BY USER  /////////
    //////////////////////////////////////////////

    /**
     * @Route("/directsnumberuser/{id}", name="api_user_directs_number_user", methods={"GET"})
     * @param DocumentManager $dm
     * @param UserManagerInterface $userManager
     * @param $id
     * @return JsonResponse
     */
    public function directsnumberByUser(DocumentManager  $dm,UserManagerInterface $userManager, $id)
    {

        $user = $dm->getRepository(User::class)->find($id);
        $directs = $user->getDirects();
        $directnumber = count($directs);

        return new Response($directnumber, 200, ['Content-Type' => 'application/json']);
    }

    ////////////////////////////////////////////////
    ///////////  MY DIRECTS NUMBER       ///////////
    ////////////////////////////////////////////////

    /**
     * @Route("/mydirectsnumber", name="api_user_mysirects", methods={"GET"})
     * @param UserManagerInterface $userManager
     * @return JsonResponse
     */
    public function getmydirectsnumber()
    {

        $user = $this->getUser();
        $directs = $user->getDirects();
        $directnumber = count($directs);

        return new Response($directnumber, 200, ['Content-Type' => 'application/json']);
    }


    //////////////////////////////////////////////
    ///////////   GET ALL USERS    ///////////////
    /// //////////////////////////////////////////

    /**
     * @Route("/allusers", name="api_user_getalll", methods={"GET"})
     * @param Request $request
     * @param UserManagerInterface $userManager
     * @param DocumentManager $dm
     * @param UserService $userservice
     * @return Response
     */
    public function getAllUser(Request $request, UserManagerInterface $userManager, DocumentManager  $dm, UserService $userservice)
    {

        $encoders = [new JsonEncoder()]; // If no need for XmlEncoder
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $users = $dm->getRepository(User::class)->findAll();

        if ($users == null){
            return new JsonResponse(["Users not found"], 500);
        }
        $allusers = $userservice->getAllUsers( $serializer, $dm,$users);

        return new Response($allusers, 200, ['Content-Type' => 'application/json']);
    }

    //////////////////////////////////////////////
    ///////////   GET MY PROFILE   ///////////////
    /// //////////////////////////////////////////

    /**
     * @Route("/profile", name="api_user_profile", methods={"GET"})
     * @param Request $request
     * @param UserManagerInterface $userManager
     * @param DocumentManager $dm
     * @param UserService $userservice
     * @return string * @Rest\View()
     */
    public function getprofile(Request $request, UserManagerInterface $userManager, DocumentManager  $dm, UserService $userservice)
    {

        $encoders = [new JsonEncoder()]; // If no need for XmlEncoder
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $currentuser = $this->getUser();
        $user = $userservice->GetOneUser( $serializer, $currentuser);

        $statusCode = 200;
        $view = $this->view($user, $statusCode);
        return $this->handleView($view);

    }


    //////////////////////////////////////////////
    ///////////  GET USER DETAIL  ////////////////
    /// //////////////////////////////////////////


    /**
     * @Route("/{id}", name="api_user_by_id", methods={"GET"})
     * @param Request $request
     * @param $id
     * @param DocumentManager $dm
     * @param UserService $userservice
     * @return Response
     */
    public function detail(Request $request,$id,DocumentManager  $dm, UserService $userservice)
    {

        $encoders = [new JsonEncoder()]; // If no need for XmlEncoder
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $user = $dm->getRepository(User::class)->find($id);

        if ($user == null){
            return new JsonResponse(["User not found"], 500);
        }

        $userdetail = $userservice->GetOneUser($serializer, $user);

        $statusCode = 200;

        $view = $this->view($userdetail, $statusCode);
        return $this->handleView($view);
    }


    //////////////////////////////////////////////
    ///////////    UPDATE USER      //////////////
    /// //////////////////////////////////////////


    /**
     * Replaces Article resource
     * @Rest\Put("/update/{id}")
     * @param int $id
     * @param Request $request
     * @param DocumentManager $dm
     * @return JsonResponse
     * @throws \Doctrine\ODM\MongoDB\LockException
     */
    public function putUser($id, Request $request,DocumentManager $dm)
    {

        $data = json_decode(
            $request->getContent(),
            true
        );

        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $address = $data['address'];
        $postalcode = $data['postalcode'];
        $city = $data['city'];
        $image = $data['image'];
        $country = $data['country'];
        $tel = $data['tel'];
        $gender = $data['gender'];
        $birthday= strtotime(substr($data['birthday'],0,24));

        $user = $dm->getRepository(User::class)->find($id);
        if ($user) {
            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $user->setAddress($address);
            $user->setPostalcode($postalcode);
            $user->setCity($city);
            $user->setCountry($country);
            $user->setTel($tel);
            $user->setPhotoName($image);
            $user->setBirthday($birthday);
            $user->setGender($gender);
            $dm->merge($user);
            $dm->flush();

        }
        // In case our PUT was a success we need to return a 200 HTTP OK response with the object as a result of PUT
        return new JsonResponse(["success" => $user->getUsername(). " has been updated!"], 200);
    }


    ////////////////////////////////////
    /////////    UP FILE      //////////
    /// ////////////////////////////////

    /**
     * @Rest\Post("/up")
     * @param Request $request
     * @Rest\View()
     * @return string
     */
    public function upAction(Request $request){
        $file = $request->files->get('File');
        $a = new FileUploader($this->getParameter('brochures_directory'));
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move($this->getParameter('brochures_directory'), $fileName);
        return $fileName;

        //$a->upload($file);
    }



    ////////////////////////////////////////////
    ///////////    UPGRADE ME      /////////////
    ////////////////////////////////////////////


    /**
     * Replaces Article resource
     * @Rest\Post("/upgrademe")
     * @param DocumentManager $dm
     * @return JsonResponse
     */
    public function UpgradeMe(DocumentManager $dm)
    {

        $ArrayCommission = [25,75,150,250,500,1500,2500];
        $CommissionService = new CommissionService();
        $user = $this->getUser();
        //$iduser = $user->getId();
        //dump($iduser);die();
        //$user = $dm->getRepository(User::class)->find($iduser);


        if($user->getLevel() == 0){
            $upline = $user->getUpline();
            $wallet = $user->getWallet();
            $balance = $wallet->getBalance();

            if ($balance < 25){
                return new JsonResponse(["Failed to upgrade" => "Solde insuffisant "], 500);
            }
            $user->setLevel(1);
            $dm->persist($user);
            $dm->flush();

            $CommissionService->UpdateWallet($upline,$ArrayCommission[0],'Income',$dm);
            $CommissionService->UpdateWallet($user,$ArrayCommission[0],'Expense',$dm);

            $CommissionService->TransactionUpgrade('Upgrade',$upline,$ArrayCommission[0],$dm);
            $CommissionService->TransactionUpgrade('Affiliation',$user,-$ArrayCommission[0],$dm);

            return new JsonResponse(["success" => $user->getUsername(). " has been upgraded to level 1"], 200);
        }

        elseif($user->getLevel() == 1){

            $wallet = $user->getWallet();
            $balance = $wallet->getBalance();

            if ($balance < 100){
                return new JsonResponse(["Failed to upgrade" => "Solde insuffisant "], 500);
            }

            $CommissionService->UpdateWallet($user,100,'Expense',$dm);
            $user->setLevel(2);
            $dm->persist($user);
            $dm->flush();

            $upline = $user->getUpline();
            $i=2;
            while ($i>=1){

                /*if ($upline == null){
                    break;
                }*/

                $level = $upline->getLevel();
                if($level >= 2){
                    $amount = $ArrayCommission[$i-1];
                    $CommissionService->UpdateWallet($upline,$amount,'Income',$dm);
                    $CommissionService->TransactionUpgrade("Upgrade",$upline,$amount,$dm);

                }
                $upline = $upline->getUpline();
                $i = $i-1 ;

            }

            return new JsonResponse(["success" => $user->getUsername().     " has been upgraded to level 2"], 200);
        }

        elseif($user->getLevel() == 2){

            $wallet = $user->getWallet();
            $balance = $wallet->getBalance();

            if ($balance < 250){
                return new JsonResponse(["Failed to upgrade" => "Solde insuffisant "], 500);
            }

            $CommissionService->UpdateWallet($user,250,'Expense',$dm);
            $user->setLevel(3);
            $dm->persist($user);
            $dm->flush();

            $upline = $user->getUpline();
            $i=3;
            while ($i>=1){

                if ($upline == null){
                    break;
                }

                $level = $upline->getLevel();
                if($level >= 3){
                    $amount = $ArrayCommission[$i-1];
                    $CommissionService->UpdateWallet($upline,$amount,'Income',$dm);
                    $CommissionService->TransactionUpgrade("Upgrade",$upline,$amount,$dm);
                }
                $upline = $upline->getUpline();
                $i = $i-1 ;

            }

            return new JsonResponse(["success" => $user->getUsername().     " has been upgraded to level 3"], 200);
        }

        elseif($user->getLevel() == 3){

            $wallet = $user->getWallet();
            $balance = $wallet->getBalance();

            if ($balance < 500){
                return new JsonResponse(["Failed to upgrade" => "Solde insuffisant "], 500);
            }

            $CommissionService->UpdateWallet($user,500,'Expense',$dm);
            $user->setLevel(4);
            $dm->persist($user);
            $dm->flush();

            $upline = $user->getUpline();
            $i=4;
            while ($i>=1){

                if ($upline == null){
                    break;
                }

                $level = $upline->getLevel();
                if($level >= 4){
                    $amount = $ArrayCommission[$i-1];
                    $CommissionService->UpdateWallet($upline,$amount,'Income',$dm);
                    $CommissionService->TransactionUpgrade("Upgrade",$upline,$amount,$dm);

                }
                $upline = $upline->getUpline();
                $i = $i-1 ;

            }

            return new JsonResponse(["success" => $user->getUsername().     " has been upgraded to level 4"], 200);
        }

        elseif($user->getLevel() == 4){

            $wallet = $user->getWallet();
            $balance = $wallet->getBalance();

            if ($balance < 1000){
                return new JsonResponse(["Failed to upgrade" => "Solde insuffisant "], 500);
            }

            $CommissionService->UpdateWallet($user,1000,'Expense',$dm);
            $user->setLevel(5);
            $dm->persist($user);
            $dm->flush();

            $upline = $user->getUpline();
            $i=5;
            while ($i>=1){

                if ($upline == null){
                    break;
                }

                $level = $upline->getLevel();
                if($level >= 5){
                    $amount = $ArrayCommission[$i-1];
                    $CommissionService->UpdateWallet($upline,$amount,'Income',$dm);
                    $CommissionService->TransactionUpgrade("Upgrade",$upline,$amount,$dm);

                }
                $upline = $upline->getUpline();
                $i = $i-1 ;

            }

            return new JsonResponse(["success" => $user->getUsername().     " has been upgraded to level 5"], 200);
        }
        elseif($user->getLevel() == 5){

            $wallet = $user->getWallet();
            $balance = $wallet->getBalance();

            if ($balance < 2500){
                return new JsonResponse(["Failed to upgrade" => "Solde insuffisant "], 500);
            }

            $CommissionService->UpdateWallet($user,2500,'Expense',$dm);
            $user->setLevel(6);
            $dm->persist($user);
            $dm->flush();

            $upline = $user->getUpline();
            $i=6;
            while ($i>=1){

                if ($upline == null){
                    break;
                }

                $level = $upline->getLevel();
                if($level >= 6){
                    $amount = $ArrayCommission[$i-1];
                    $CommissionService->UpdateWallet($upline,$amount,'Income',$dm);
                    $CommissionService->TransactionUpgrade("Upgrade",$upline,$amount,$dm);

                }
                $upline = $upline->getUpline();
                $i = $i-1 ;

            }

            return new JsonResponse(["success" => $user->getUsername().     " has been upgraded to level 6"], 200);
        }
        elseif($user->getLevel() == 6){

            $wallet = $user->getWallet();
            $balance = $wallet->getBalance();

            if ($balance < 5000){
                return new JsonResponse(["Failed to upgrade" => "Solde insuffisant "], 500);
            }

            $CommissionService->UpdateWallet($user,5000,'Expense',$dm);
            $user->setLevel(7);
            $dm->persist($user);
            $dm->flush();

            $upline = $user->getUpline();
            $i=7;
            while ($i>=1){

                if ($upline == null){
                    break;
                }

                $level = $upline->getLevel();
                if($level >= 7){
                    $amount = $ArrayCommission[$i-1];
                    $CommissionService->UpdateWallet($upline,$amount,'Income',$dm);
                    $CommissionService->TransactionUpgrade("Upgrade",$upline,$amount,$dm);

                }
                $upline = $upline->getUpline();
                $i = $i-1 ;

            }

            return new JsonResponse(["success" => $user->getUsername().     " has been upgraded to level 7"], 200);
        }



    }




}