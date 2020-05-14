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
use Symfony\Component\HttpFoundation\Response;
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

    /**
     * @param SerializerInterface $serializer
     * @param PersistentCollection $directs
     * @return string
     * @Rest\View()
     */
    public function getmyDirects(SerializerInterface $serializer, PersistentCollection $directs)
    {

        $array = array();
        $treeArray = array();
        foreach ($directs as $direct){

            $ds = $direct->getDirects();
            //$tree = $this->getmyDirects($serializer,$ds);
            dump($direct->getUsername());


            $treeArray[] = [
                "label" => $direct->getUsername(),
                "children" => $this->getmyDirects($serializer,$ds)
            ];


            //array_push($array,$treeArray);
        }

        $jsonObject = $serializer->serialize($treeArray, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object;
            }
        ]);

        //die();
        return $jsonObject;


        /*foreach ($directs as $direct) {

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
                    'children'=>$treeArray2
                ];
            }
            $treeArray[] = [
                'label' => $direct->getUsername(),
                'children'=> $treeArray1
            ];
        }*/









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