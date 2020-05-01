<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 23/04/2020
 * Time: 01:52
 */

namespace App\Controller\Api;

use App\Document\User;
use App\Document\Partnership;
use App\Document\Direct;
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
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/affiliation")
 */
class ApiAffiliationController extends AbstractController
{

    //////////////////////////////////////////////
    ///////////  ADD USER AFFILIATION ////////////
    /// //////////////////////////////////////////

    /**
     * @Route( "/new/{id}", name="api_user_affiliation", methods={"POST"})
     * @param User $user
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function newAffiliation(SerializerInterface $serializer, Request $request,  UserManagerInterface $userManager,DocumentManager  $dm, $id){


        $partnership = new Partnership();
        $upline = $dm->getRepository(User::class)->find($id);
        $codeupline= $upline->getCodeUser();
        $data = json_decode(
            $request->getContent(),
            true
        );

        $partnership->setCodeUpline($codeupline);
        $dm->persist($partnership);
        $dm->flush();

        $username = $data['username'];
        $password = $data['password'];
        $email = $data['email'];
        $codeuser = substr(md5(time()), 0, 15);

        $user = new User();
        $user->setUsername($username);
          $user  ->setPlainPassword($password);
            $user->setEmail($email);
            $user->setEnabled(true);
            $user->setRoles(array('ROLE_USER'));
            $user->setSuperAdmin(false);
            $user->setCodeUser($codeuser);
            $user->setLevel('0');
            $user->setPartnership($partnership);
            $user->setUpline($upline);
            //dump($user);die();

        try {
            $userManager->updateUser($user, true);
        } catch (\Exception $e) {
            return new JsonResponse(["error" => $e->getMessage()], 500);
        }

        //$entityAsArray = $this->serializer->normalize($entity, null);






        /*$upline->setDirects($arrayUser);
        $dm->persist($upline);
        $dm->flush();*/


        $partnership->setCodeDownline($user->getCodeUser());

        $partnership->setCodeUpline($codeupline);
        $dm->persist($partnership);
        $dm->flush();


        return new JsonResponse(["success" => $user->getUsername(). " has been registered!"], 200);


    }
}