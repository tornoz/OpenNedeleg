<?php

namespace App\Controller;

use App\Entity\ListItem;
use App\Entity\Reservation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Entity\UserList;
use App\Form\UserListType;
use Symfony\Component\HttpFoundation\Request;


class ListController extends AbstractController
{
    /**
     * @Route("/list/{token}", name="list")
     * @ParamConverter("userList", options={"mapping": {"token": "token"}})
     */
    public function list(Request $request, UserList $userList): Response
    {
        $listForm = $this->createForm(UserListType::class, $userList);

        $listForm->handleRequest($request);

        if($listForm->isSubmitted() && $listForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /** @var $listData UserList */
            $listData = $listForm->getData();
            $em->persist($listData);
            $em->flush();
            return $this->redirect($request->getUri());
        }
        

        return $this->render('list/index.html.twig', [
            'listForm' => $listForm->createView(),
            'userList' => $userList,
            'user' => $userList->getWriter()
        ]);
    }

    /**
     * @Route("/reserve/{token}/{item}", name="reserve")
     * @ParamConverter("userList", options={"mapping": {"token": "token"}})
     * @ParamConverter("item", options={"mapping": {"item": "id"}})
     */
    public function reserve(Request $request, UserList $userList, ListItem $item): Response
    {
        if($item->getReservations()->count() > 0) {
            return $this->redirectToRoute('list', ['token' => $userList->getToken()]);
        }

        $reservation = new Reservation();
        $reservation->setReserver($userList->getWriter());
        $reservation->setItem($item);
        $em = $this->getDoctrine()->getManager();
        $em->persist($reservation);
        $em->flush();

        return $this->redirectToRoute('list', ['token' => $userList->getToken()]);
    }

    /**
     * @Route("/unreserve/{token}/{item}", name="unreserve")
     * @ParamConverter("userList", options={"mapping": {"token": "token"}})
     * @ParamConverter("item", options={"mapping": {"item": "id"}})
     */
    public function unreserve(Request $request, UserList $userList, ListItem $item): Response
    {
        if($item->getReservations()->count() == 0
        || $item->getReservations()->first()->getReserver() != $userList->getWriter() ) {
            return $this->redirectToRoute('list', ['token' => $userList->getToken()]);
        }

        $reservation = $item->getReservations()->first();
        $em = $this->getDoctrine()->getManager();
        $item->removeReservation($reservation);
        $em->remove($reservation);
        $em->flush();

        return $this->redirectToRoute('list', ['token' => $userList->getToken()]);
    }


    /**
     * @Route("/get/{token}/{item}", name="get")
     * @ParamConverter("userList", options={"mapping": {"token": "token"}})
     * @ParamConverter("item", options={"mapping": {"item": "id"}})
     */
    public function getAction(Request $request, UserList $userList, ListItem $item): Response
    {
        $em = $this->getDoctrine()->getManager();
        $reservation = null;
        if($item->getReservations()->count() < 1) {
            $reservation = new Reservation();
            $reservation->setReserver($userList->getWriter());
            $reservation->setItem($item);
        } else {
            $reservation = $item->getReservations()->first();
        }
        $reservation->setStatus(Reservation::GOT);

        $em->persist($reservation);
        $em->flush();

        return $this->redirectToRoute('list', ['token' => $userList->getToken()]);
    }

}
