<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 05/05/2020
 * Time: 02:59
 */

namespace App\Service;

use App\Document\Coupon;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Serializer\SerializerInterface;

class CouponService
{

    //////////////////////////
    //////  ALL COUPONS //////
    //////////////////////////

    /**
     * @param Coupon $coupn
     * @param DocumentManager $dm
     * @return string
     */
    public function getAllCoupons(SerializerInterface $serializer, DocumentManager  $dm )
    {

        $coupons = $dm->getRepository(Coupon::class)->findAll();


        foreach ($coupons as $coupon){

            $formatted= $serializer->serialize(
                $coupon,
                'json',[
                    'circular_reference_handler' => function ($object) {
                        return $object->getId();
                    }
                ]
            );


        }
        return $formatted;

    }

}