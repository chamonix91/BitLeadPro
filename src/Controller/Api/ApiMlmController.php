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


    /**
     * @Route( "/{id}", name="api_user_mlm", methods={"get"})
     * @param User $user
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function myMyMLM (MlmService $MlmService,Request $request,DocumentManager  $dm, $id, SerializerInterface $serializer, User $user){
        
        /*$user = $dm->getRepository(User::class)->find($id);

        $directs = $user->getDirects();

        $dir= $MlmService->AllDirects($serializer, $user, $directs);
        $tab = array();

        foreach ($directs as $direct) {

            dump($direct->getUsername());

            array_push($tab, $direct->getUsername());
            $directs= $MlmService->AllDirects($serializer, $direct, $directs);






        }


        //dump($tab);
        die();







        return $response = new Response ($directs, Response::HTTP_OK);*/


    }

}