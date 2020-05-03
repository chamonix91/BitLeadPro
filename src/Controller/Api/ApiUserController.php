<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 13/04/2020
 * Time: 13:16
 */

namespace App\Controller\Api;

use App\Document\User;
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
    public function detail(Request $request,$id,DocumentManager  $dm)
    {

        $user = new User() ;
        //$this->denyAccessUnlessGranted('view', $user);
        $user = $dm->getRepository(User::class)->find($id);

        $directs = $user->getDirects();
        $c = count($directs);
        dump($c);die();

        return new JsonResponse($this->serialize($user), 200);
    }
    protected function serialize(User $user)
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $json = $serializer->serialize($user, 'json');
        return $json;
    }

    //////////////////////////////////////////////
    ///////////  GET CURRENT USER  ///////////////
    /// //////////////////////////////////////////

    /**
     * @Route("/current", name="api_user_detail", methods={"GET"})
     * @param User $user
     * @return JsonResponse
     */
    public function getloggedtUser(Request $request, UserManagerInterface $userManager)
    {

        $encoders = [new JsonEncoder()]; // If no need for XmlEncoder
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $user = $this->getUser();

        $jsonObject = $serializer->serialize($user, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);



        return new Response($jsonObject, 200, ['Content-Type' => 'application/json']);
    }



}