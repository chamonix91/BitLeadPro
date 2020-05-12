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
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
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

use FOS\RestBundle\Controller\Annotations as Rest;


use JMS\Serializer\SerializationContext;
/**
 * @Route("/user")
 */
class ApiUserController extends FOSRestController
{


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

        $userdetail = $userservice->GetOneUser($serializer, $user);
        //dump($user);die();






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
     * @return
     */
    public function putArticle(int $id, Request $request,DocumentManager $dm)
    {
        $user = $dm->getRepository(User::class)->find($id);
        if ($user) {
            $user->setFirstname($request->get('firstname'));
            $user->setLastname($request->get('lastname'));
            $user->setAddress($request->get('address'));
            $user->setPostalcode($request->get('postalcode'));
            $user->setCity($request->get('city'));
            $user->setCountry($request->get('country'));

        }
        // In case our PUT was a success we need to return a 200 HTTP OK response with the object as a result of PUT
        return new JsonResponse(["success" => $user->getUsername(). " has been registered!"], 200);    }





}