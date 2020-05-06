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


    //////////////////////////////////////////////
    ///////////  GET USER DETAIL  ////////////////
    /// //////////////////////////////////////////

    /**
     * @Route("/{id}", name="api_user_detail", methods={"GET"})
     * @param User $user
     * @return JsonResponse
     */
    public function detail(Request $request,$id,DocumentManager  $dm, UserService $userservice)
    {

        $encoders = [new JsonEncoder()]; // If no need for XmlEncoder
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $user = new User() ;

        $user = $dm->getRepository(User::class)->find($id);
        
        $userdetail = $userservice->GetOneUser( $serializer, $user);

        return new Response($userdetail, 200, ['Content-Type' => 'application/json']);
    }
    /*protected function serialize(User $user)
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $json = $serializer->serialize($user, 'json');
        return $json;
    }*/

    //////////////////////////////////////////////
    ///////////  GET CURRENT USER  ///////////////
    /// //////////////////////////////////////////

    /**
     * @Route("/current", name="api_user_current", methods={"GET"})
     * @param User $user
     * @return JsonResponse
     */
    public function getloggedtUser(Request $request, UserManagerInterface $userManager, UserService $userservice)
    {

        $encoders = [new JsonEncoder()]; // If no need for XmlEncoder
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $user = $this->getUser();

        $current = $userservice->GetOneUser( $serializer, $user);

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
     * @Route("/allusers", name="api_user_getall", methods={"GET"})
     * @param User $user
     * @return JsonResponse
     */
    public function getAllUser(Request $request, UserManagerInterface $userManager, DocumentManager  $dm, UserService $userservice)
    {

        $encoders = [new JsonEncoder()]; // If no need for XmlEncoder
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $users = $userservice->getAllUsers( $serializer, $dm);


        return new Response($users, 200, ['Content-Type' => 'application/json']);
    }



}