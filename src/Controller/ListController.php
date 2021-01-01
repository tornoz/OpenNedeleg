<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Entity\UserList;
use App\Form\UserListType;



class ListController extends AbstractController
{
    /**
     * @Route("/list/{token}", name="list")
     * @ParamConverter("userList", options={"mapping": {"token": "token"}})
     */
    public function list(Request $request, UserList $userList): Response
    {
        $listForm = $this->createForm(UserListType::class, $userList);

        

        return $this->render('list/index.html.twig', [
            'listForm' => $listForm->createView(),
            'userList' => $userList,
            'user' => $userList->getWriter()
        ]);
    }
}
