<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 05/05/2020
 * Time: 02:22
 */

namespace App\Controller\Api;

use App\Document\Coupon;
use App\Service\CouponService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/coupon")
 */
class ApiCouponController
{

    //////////////////////////////////////////////
    ///////////  GET ALL COUPONS   ///////////////
    //////////////////////////////////////////////

    /**
     * @Route("/allcoupons", name="api_coupon_allcoupons", methods={"GET"})
     * @param User $user
     * @return JsonResponse
     */
    public function getmyalldirectsUser(Request $request, DocumentManager  $dm, CouponService $couponservice)
    {

        $encoders = [new JsonEncoder()]; // If no need for XmlEncoder
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $coupons = $couponservice->getAllCoupons( $serializer, $dm);





        return new Response($coupons, 200, ['Content-Type' => 'application/json']);
    }

}