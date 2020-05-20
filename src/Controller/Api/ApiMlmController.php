<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 29/04/2020
 * Time: 03:53
 */

namespace App\Controller\Api;

use App\Document\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Serializer\SerializerInterface;
use App\Service\MlmService;

/**
 * @Route("/mlm")
 */
class ApiMlmController extends AbstractController
{

    /////////////////////////
    ///  DISPLAY MY MLM  ////
    /////////////////////////

    /**
     * @Route( "/{id}", name="api_user_mlm", methods={"get"})
     * @param MlmService $MlmService
     * @param Request $request
     * @param DocumentManager $dm
     * @param $id
     * @param SerializerInterface $serializer
     * @param User $user
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function MyMLM(MlmService $MlmService, Request $request, DocumentManager $dm, $id, SerializerInterface $serializer, User $user)
    {

        $user = $dm->getRepository(User::class)->find($id);
        $directs = $user->getDirects();


        $tree = $MlmService->getmyMLM($serializer, $directs);

        if ($user->getPhotoName()== null) {
            $mytree = [
                "data" => [
                    "avatar" => "placeholder.jpg",
                    "firstname" => $user->getFirstname(),
                    "lastname" => $user->getLastname(),
                    "img" => "0"
                ],
                "label" => $user->getUsername(),
                "expanded" => "true",
                "styleClass" => "ui-person",
                "children" => json_decode($tree, true)
            ];
        }
        else{

            $treeArray[] = [
                "data" => [
                    "avatar"=> $user->getPhotoName(),
                    "firstname"=> $user->getFirstname() ,
                    "lastname"=> $user->getLastname() ,
                    "img"=> "1"
                ],
                "label" => $user->getUsername(),
                "expanded" => "true",
                "styleClass" => "ui-person",
                "children" => json_decode($tree, true)
            ];
        }

        $jsonObject = $serializer->serialize($mytree, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object;
            }
        ]);




        return new Response($jsonObject, 200, ['Content-Type' => 'application/json']);


    }

    /////////////////////////
    ///  GET MY DOWNLINES ///
    /////////////////////////

    /**
     * @Route( "/mydownlines/{id}", name="api_mlm_downlines", methods={"get"})
     * @param MlmService $MlmService
     * @param Request $request
     * @param DocumentManager $dm
     * @param $id
     * @param SerializerInterface $serializer
     * @param User $user
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function MyDownlines(MlmService $MlmService, Request $request, DocumentManager $dm, $id, SerializerInterface $serializer, User $user)
    {

        $user = $dm->getRepository(User::class)->find($id);
        $directs = $user->getDirects();
        $downlines = array();
        $tree = json_decode($MlmService->getmyMLM($serializer, $directs));

        dump($tree[0]);die();

        $downlines = $tree["label"];
        $jsonObject = $serializer->serialize($downlines, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object;
            }
        ]);


        return new Response($jsonObject, 200, ['Content-Type' => 'application/json']);


    }

}