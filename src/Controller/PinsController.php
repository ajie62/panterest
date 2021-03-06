<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Form\PinType;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PinsController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="app_home", methods={"GET"})
     */
    public function index(PinRepository $pinRepository): Response
    {
        $pins = $pinRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('pins/index.html.twig', [
            'pins' => $pins,
        ]);
    }

    /**
     * @Route("/pins/create", name="app_pins_create", methods={"GET|POST"})
     */
    public function create(Request $request): Response
    {
        $pin = new Pin();

        $form = $this->createForm(PinType::class, $pin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($pin);
            $this->em->flush();

            $this->addFlash('success', 'Pin successfully created!');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('pins/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/pins/{id<[0-9]+>}", name="app_pins_show", methods={"GET"})
     */
    public function show(Pin $pin): Response
    {
        return $this->render('pins/show.html.twig', [
            'pin' => $pin,
        ]);
    }

    /**
     * @Route("/pins/{id<[0-9]+>}/edit", name="app_pins_edit", methods={"GET|PUT"})
     */
    public function edit(Pin $pin, Request $request): Response
    {
        $form = $this->createForm(PinType::class, $pin, [
            'method' => 'PUT',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            $this->addFlash('success', 'Pin successfully updated!');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('pins/edit.html.twig', [
            'pin' => $pin,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/pins/{id<[0-9]+>}", name="app_pins_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Pin $pin)
    {
        if ($this->isCsrfTokenValid('pin_deletion_' . $pin->getId(), $request->request->get('csrf_token'))) {
            $this->em->remove($pin);
            $this->em->flush();

            $this->addFlash('info', 'Pin successfully deleted!');
        }

        return $this->redirectToRoute('app_home');
    }
}
