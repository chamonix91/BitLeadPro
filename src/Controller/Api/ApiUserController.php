<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 13/04/2020
 * Time: 13:16
 */

namespace App\Controller\Api;

use App\Document\User;

use App\Service\UserService;
use Doctrine\ODM\MongoDB\DocumentManager;
use FOS\UserBundle\Model\UserManagerInterface;
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


use JMS\Serializer\SerializationContext;
/**
 * @Route("/user")
 */
class ApiUserController extends AbstractController
{



    /**
     * @Route("/current", name="api__logged_user", methods={"GET"})
     * @param User $user
     * @return JsonResponse
     */
    public function getcurrenttUser(Request $request, UserManagerInterface $userManager, UserService $userservice)
    {

        $encoders = [new JsonEncoder()]; // If no need for XmlEncoder
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);


        $user = $this->getUser();



        $formatted[] = [
            'id' => $user->getId(),
            'firstname' => $user->getfirstname(),
            'email' => $user->getEmail(),
            'lastname' => $user->getlastname(),
            'address' => $user->getaddress(),
            'tel' => $user->gettel(),
            'gender' => $user->getgender(),
            'postalcode' => $user->getpostalcode(),
            'city' => $user->getcity(),
            'country' => $user->getcountry(),
            'image' => $user->getimage(),
            'level' => $user->getlevel(),
            'birthday' => $user->getbirthday(),
            'username' => $user->getUsername(),
            'created_date' => $user->getCreatedDate(),
            'role'=> $user->getRoles()
        ];
        $current= $serializer->serialize(
            $formatted,
            'json',[
                'circular_reference_handler' => function ($object) {
                    return $object->getId();
                }
            ]
        );
        return new Response($current, 200, ['Content-Type' => 'application/json']);
    }


    //////////////////////////////////////////////
    ///////////  MY ALL DIRECTS    ///////////////
    /// //////////////////////////////////////////

    /**
     * @Route("/myalldirects", name="api_user_mysirects", methods={"GET"})
     * @param User $user
     * @return JsonResponse
     */
    public function getmyalldirectsUser(Request $request, UserManagerInterface $userManager)
    {

        $encoders = [new JsonEncoder()]; // If no need for XmlEncoder
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $user = $this->getUser();
        //$myuser = (get_class($user));

        $directs = $user->getDirects();


        $jsonObject = $serializer->serialize($directs, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ],[AbstractNormalizer::IGNORED_ATTRIBUTES => ['firstname']]);



        return new Response($jsonObject, 200, ['Content-Type' => 'application/json']);
    }


    //////////////////////////////////////////////
    ///////////   GET ALL USERS    ///////////////
    /// //////////////////////////////////////////

    /**
     * @Route("/allusers", name="api_user_getalll", methods={"GET"})
     * @param User $user
     * @return JsonResponse
     */
    public function getAllUser(Request $request, UserManagerInterface $userManager, DocumentManager  $dm, UserService $userservice)
    {


        $encoders = [new JsonEncoder()]; // If no need for XmlEncoder
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        //$users = $userservice->getAllUsers( $serializer, $dm);
        $users = $dm->getRepository(User::class)->findAll();




        return new Response($users, 200, ['Content-Type' => 'application/json']);
    }

    //////////////////////////////////////////////
    ///////////   GET MY PROFILE   ///////////////
    /// //////////////////////////////////////////

    /**
     * @Route("/profile", name="api_user_profile", methods={"GET"})
     * @param User $user
     * @return JsonResponse
     */
    public function getprofile(Request $request, UserManagerInterface $userManager, DocumentManager  $dm, UserService $userservice)
    {

        $encoders = [new JsonEncoder()]; // If no need for XmlEncoder
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $currentuser = $this->getUser();

        $user = $userservice->GetOneUser( $serializer, $currentuser);

        return new Response($user, 200, ['Content-Type' => 'application/json']);

    }


    //////////////////////////////////////////////
    ///////////  GET USER DETAIL  ////////////////
    /// //////////////////////////////////////////


    /**
     * @Route("/{id}", name="api_user_by_id", methods={"GET"})
     * @param User $user
     * @return JsonResponse
     */
    public function detail(Request $request,$id,DocumentManager  $dm, UserService $userservice)
    {

        $encoders = [new JsonEncoder()]; // If no need for XmlEncoder
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);



        $user = $dm->getRepository(User::class)->find($id);

        $userdetail = $userservice->GetOneUser($serializer, $user);
        //dump($user);die();






        return new Response($userdetail, 200, ['Content-Type' => 'application/json']);
    }





}