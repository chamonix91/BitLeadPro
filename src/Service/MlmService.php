<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 29/04/2020
 * Time: 04:01
 */

namespace App\Service;

use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Serializer\SerializerInterface;

class MlmService
{

    /////////////////////////
    ///  get all directs ////
    /////////////////////////

    public function AllDirects( SerializerInterface $serializer, User $user )
    {

        $directs = $user->getDirects();


        /*$jsonObject = $serializer->serialize($directs, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object;
            }
        ]);*/



        return $directs;


    }

    /////////////////////////
    ////  get my directs ////
    /////////////////////////

    public function getmyDirects( Array $tab, User $user )
    {

        foreach ($directs as $direct) {
            $tabdir = array();
            $ds = $MlmService->AllDirects($serializer, $direct);
            foreach ($ds as $d ){
                array_push($tabdir, $d->getUsername());
            }
            array_push($tab, $tabdir);


        }



        return $directs;


    }


}