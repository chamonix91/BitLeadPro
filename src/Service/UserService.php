<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 05/05/2020
 * Time: 03:01
 */

namespace App\Service;

use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class UserService
{
    //////////////////////////
    //////   ALL USERS  //////
    //////////////////////////

    /**
     * @param SerializerInterface $serializer
     * @param DocumentManager $dm
     * @return string
     */
    public function getAllUsers(SerializerInterface $serializer, DocumentManager  $dm )
    {

        $users = $dm->getRepository(User::class)->findAll();

        foreach ($users as $user){


            $formatted[] = [
                'id' => $user->getId(),
                'firstname' => $user->getfirstname(),
                'email' => $user->getEmail(),
                'lastname' => $user->getlastname(),
                'address' => $user->getaddress(),
                'postalcode' => $user->getpostalcode(),
                'tel' => $user->gettel(),
                'gender' => $user->getgender(),
                'city' => $user->getcity(),
                'country' => $user->getcountry(),
                'image' => $user->getimage(),
                'level' => $user->getlevel(),
                'birthday' => $user->getbirthday(),
                'username' => $user->getUsername(),
                'created_date' => $user->getCreatedDate(),
                'role'=> $user->getRoles()
            ];

        }

        $allusers= $serializer->serialize(
            $formatted,
            'json',[
                'circular_reference_handler' => function ($object) {
                    return $object->getId();
                }
            ]
        );


        return $allusers;

    }

    //////////////////////////
    //////  GET ONE USER /////
    //////////////////////////

    /**
     * @param SerializerInterface $serializer
     * @param User $user
     * @return string
     */
    public function GetOneUser(SerializerInterface $serializer, User $user )
    {

            $formatted[] = [
                'id' => $user->getId(),
                'firstname' => $user->getfirstname(),
                'email' => $user->getEmail(),
                'lastname' => $user->getlastname(),
                'address' => $user->getaddress(),
                'tel' => $user->gettel(),
                'gender' => $user->getgender(),
                'postalcode' => $user->getpostalcode(),
                'city' => $user->getcity(),
                'country' => $user->getcountry(),
                'image' => $user->getimage(),
                'level' => $user->getlevel(),
                'birthday' => $user->getbirthday(),
                'username' => $user->getUsername(),
                'created_date' => $user->getCreatedDate(),
                'role'=> $user->getRoles()
            ];



        $user= $serializer->serialize(
            $formatted,
            'json',[
                'circular_reference_handler' => function ($object) {
                    return $object->getId();
                }
            ]
        );


        return $user;

    }

}