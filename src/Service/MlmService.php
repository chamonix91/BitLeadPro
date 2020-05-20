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
    ////   GET MY MLM    ////
    /////////////////////////

    /**
     * @param SerializerInterface $serializer
     * @param PersistentCollection $directs
     * @return array|string
     * @Rest\View()
     */
    public function getmyMLM(SerializerInterface $serializer, PersistentCollection $directs)
    {

        $treeArray = array();
        foreach ($directs as $direct){

            $ds = $direct->getDirects();

            if ($direct->getPhotoName()== null){
            $treeArray[] = [
                "data" => [
                    "avatar"=> "placeholder.jpg",
                    "firstname"=> $direct->getFirstname() ,
                    "lastname"=> $direct->getLastname() ,
                    "img"=> "0"
                ],
                "label" => $direct->getUsername(),
                "type" => "person",
                "styleClass" => "ui-person",
                "children" => json_decode($this->getmyMLM($serializer,$ds),true)
            ];
            }
            else {
                $treeArray[] = [
                    "data" => [
                        "avatar"=> $direct->getPhotoName(),
                        "firstname"=> $direct->getFirstname() ,
                        "lastname"=> $direct->getLastname() ,
                        "img"=> "1"
                    ],
                    "label" => $direct->getUsername(),
                    "type" => "person",
                    "styleClass" => "ui-person",
                    "children" => json_decode($this->getmyMLM($serializer,$ds),true)
                ];
            }
        }
        $jsonObject = $serializer->serialize($treeArray, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object;
            }
        ]);

        return $jsonObject;

    }

    /////////////////////////
    ////   GET DOWNLINES ////
    /////////////////////////

    public function GetDownlines(SerializerInterface $serializer, PersistentCollection $directs, Array $downlines)
    {


    }


}