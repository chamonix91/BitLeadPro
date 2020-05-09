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
use Doctrine\ODM\MongoDB\PersistentCollection;
use Symfony\Component\Serializer\SerializerInterface;

class MlmService
{

    /////////////////////////
    ///  get all directs ////
    /////////////////////////

    public function AllDirects(SerializerInterface $serializer, User $user, PersistentCollection $directs)
    {

        $directs = $user->getDirects();
        $tab = array();

        foreach ($directs as $direct) {

            $formatted[] = [
                'label' => $direct->getUsername(),
                'children' => $direct->getId()

            ];
        }
        $jsonObject = $serializer->serialize($formatted, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object;
            }
        ]);

        return $formatted;


    }

    /////////////////////////
    ////  get my directs ////
    /////////////////////////

    public function getmyDirects(SerializerInterface $serializer, PersistentCollection $directs)
    {

        foreach ($directs as $direct) {

            $tab = array();
            $ds1 = $direct->getDirects();


            foreach ($ds1 as $d1) {
                array_push($tab, $d1->getUsername());
                $ds2 = $direct->getDirects();
                $tab1 = array();

                foreach($ds2 as $d2){
                    array_push($tab1, $d2->getUsername());
                    $ds3 = $d2->getDirects();
                    $tab2 = array();

                    foreach($ds3 as $d3){
                        array_push($tab2, $d3->getUsername());
                    }



                    $treeArray2[] = [
                        'label' => $d2->getUsername(),
                        'children'=>$tab2
                    ];
                    //dump($treeArray2);
                }
                //die();

                $treeArray1[] = [
                    'label' => $d1->getUsername(),
                    'children'=>$tab1
                ];
            }
            $treeArray[] = [
                'label' => $direct->getUsername(),
                'children'=> $tab
            ];
        }


        $jsonObject = $serializer->serialize($treeArray, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object;
            }
        ]);





        return $jsonObject;


    }

    /////////////////////////
    ////   CREATE MLM    ////
    /////////////////////////

    public function CreateMlm(SerializerInterface $serializer, User $user, PersistentCollection $directs)
    {

        $directs = $user->getDirects();
        $tab = array();

        foreach ($directs as $direct) {

            $formatted[] = [
                'label' => $direct->getUsername(),
                'children' => $direct->getId()
            ];
        }
        $jsonObject = $serializer->serialize($formatted, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object;
            }
        ]);

        return $formatted;


    }


}