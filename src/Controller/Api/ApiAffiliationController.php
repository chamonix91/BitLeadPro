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
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/affiliation")
 */
class ApiAffiliationController extends AbstractController
{


    /**
     * @Route("/{id}", name="api_user_affiliation", methods={"POST"})
     * @param User $user
     * @return JsonResponse
     */
    public function newAffiliation(Request $request,  UserManagerInterface $userManager){


        $partnership = new Partnership();

        $username = $data['username'];
        $password = $data['password'];
        $email = $data['email'];
        $user = new User();
        $user
            ->setUsername($username)
            ->setPlainPassword($password)
            ->setEmail($email)
            ->setEnabled(true)
            ->setRoles(array('ROLE_USER'))
            ->setSuperAdmin(false)
        ;


    }
}