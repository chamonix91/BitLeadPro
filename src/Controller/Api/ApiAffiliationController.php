<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 23/04/2020
 * Time: 01:52
 */

namespace App\Controller\Api;

use App\Document\User;
use App\Document\Coupon;
use App\Document\Direct;
use App\Document\Wallet;
use App\Service\CommissionService;
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
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

/**
 * @Route("/registration")
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


        $data = json_decode(
            $request->getContent(),
            true
        );

        $username = $data['username'];
        $password = $data['password'];
        $email = $data['email'];
        //$codeuser = substr(md5(time()), 0, 15);
        $coupon = $data['coupon'];
        $withcoupon = $data['withcoupon'];
        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $address = $data['address'];
        $postalcode = $data['postalcode'];
        $city = $data['city'];
        $country = $data['country'];


        $user = new User();
        $user->setUsername($username);
          $user  ->setPlainPassword($password);
            $user->setEmail($email);
            $user->setEnabled(true);
            $user->setRoles(array('ROLE_USER'));
            $user->setSuperAdmin(false);
            $user->setLevel(0);
            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $user->setAddress($address);
            $user->setPostalcode($postalcode);
            $user->setCity($city);
            $user->setCountry($country);
            $user->setPhotoName($image);


            $couponn =$dm->getRepository(Coupon::class)->findOneBy(array('code'=>$coupon));

            if($withcoupon == "0"){

                $upline = $dm->getRepository(User::class)->find($id);
            }

            if ($withcoupon == "1"){
                $upline = $couponn->getOwner();
            }

            $user->setUpline($upline);

            $wallet = new Wallet();
            $wallet->setExpenses(0);
            $wallet->setBalance(0);
            $dm->persist($wallet);
            $dm->flush();

            $user->setWallet($wallet);

        try {
            $userManager->updateUser($user, true);
        } catch (\Exception $e) {
            return new JsonResponse(["error" => $e->getMessage()], 500);
        }


        $couponObject = new Coupon();
        $couponObject->setName('Affiliation');
        $couponObject->setActive('1');
        $couponObject->setType('starter');
        $couponObject->setValue(0);
        $couponObject->setCode($user->getUsername());
        $couponObject->setOwner($user);
        $dm->persist($couponObject);
        $dm->flush();

        return new JsonResponse(["success" => $user->getUsername(). " id " .$user->getId(). " has been registered!"], 200);


    }


    ///////////////////////////////////////////////////////
    ///////////  ADD USER AFFILIATION TO ADMIN ////////////
    ///////////////////////////////////////////////////////

    /**
     * @Route( "/new", name="api_user_affiliation_to_admin", methods={"POST"})
     * @param User $user
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function newToAdminAffiliation(SerializerInterface $serializer, Request $request,  UserManagerInterface $userManager,DocumentManager $dm){

        $data = json_decode(
            $request->getContent(),
            true
        );

        $username = $data['username'];
        $password = $data['password'];
        $email = $data['email'];
        //$codeuser = substr(md5(time()), 0, 15);
        $coupon = $data['coupon'];
        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $address = $data['address'];
        $postalcode = $data['postalcode'];
        $city = $data['city'];
        $country = $data['country'];




        $user = new User();
        $user->setUsername($username);
        $user ->setPlainPassword($password);
        $user->setEmail($email);
        $user->setEnabled(false);
        $user->setRoles(array('ROLE_USER'));
        $user->setSuperAdmin(false);
        $user->setLevel(0);
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setAddress($address);
        $user->setPostalcode($postalcode);
        $user->setCity($city);
        $user->setCountry($country);

        $couponn =$dm->getRepository(Coupon::class)->findOneBy(array('code'=>$coupon));
        $upline = $couponn->getOwner();

        $user->setUpline($upline);

        $wallet = new Wallet();
        $wallet->setExpenses(0);
        $wallet->setBalance(0);
        $dm->persist($wallet);
        $dm->flush();

        $user->setWallet($wallet);


        try {
            $userManager->updateUser($user, true);
        } catch (\Exception $e) {
            return new JsonResponse(["error" => $e->getMessage()], 500);
        }

        $couponObject = new Coupon();
        $couponObject->setName('Affiliation');
        $couponObject->setActive('1');
        $couponObject->setType('starter');
        $couponObject->setValue(0);
        $couponObject->setCode($user->getUsername());
        $couponObject->setOwner($user);
        $dm->persist($couponObject);
        $dm->flush();

        return new JsonResponse(["success" => $user->getUsername(). " has been registered!"], 200);


    }

    //////////////////////////////////////////////
    ///////////  GET AFFILIATE CODE  /////////////
    /// //////////////////////////////////////////

    /**
     * @Route( "/getcodeupline/{id}", name="api_user_affiliation_code", methods={"GET"})
     * @param User $user
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function GetAffiliateCode(Request $request,$id,DocumentManager  $dm)
    {

        $user = $dm->getRepository(User::class)->find($id);
        $code= $user->getUsername();
        return new JsonResponse($code, 200);
    }



    //////////////////////////////////////////////
    ///////////   FORGOT PASSWORD  ///////////////
    /// //////////////////////////////////////////

    /**
     * @Route("/forgotpwd", name="api_user_forgotpwd", methods={"POST"})
     * @param User $user
     * @return JsonResponse
     */
    public function forgottenPassword(Request $request, UserManagerInterface $userManager, DocumentManager  $dm, \Swift_Mailer $mailer)
    {

        $data = json_decode(
            $request->getContent(),
            true
        );
        $user_email = $data['email'];
        $user =$dm->getRepository(User::class)->findOneBy(array('email'=>$user_email));



        if ($user == null){

            return new JsonResponse(["this email does not exist!"], 404);
        }

        $message = (new \Swift_Message('Recovering Password From BitLeadPro.Com'))
            ->setFrom('dreamlifedev@gmail.com')
            ->setTo('chamseddine.bezzaouia@gmail.com')
            ->setBody('<p>Your New Password Is Orama123</p>'
            );

        $mailer->send($message);

        return new JsonResponse(["success.Your email has been send!"], 200);
    }






}