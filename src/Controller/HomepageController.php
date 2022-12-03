<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ContactRepository $contactRepository,
    )
    {
    }

    #[Route('/', name: 'app_homepage')]
    public function index(): Response
    {
        $contacts = $this->contactRepository->findAll();

        return $this->render('homepage/index.html.twig', [
            'contacts' => $contacts,
        ]);
    }

    #[Route('/{id}', name: 'app_edit', methods: ['GET', 'POST'])]
    #[Route('/new', name: 'app_new', methods: ['GET', 'POST'])]
    public function newAction(Request $request, Contact $contact = null): Response
    {
        if (!$contact) {
            $contact = new Contact();
        }

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($contact);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('homepage/new.html.twig', [
            'contact' => $contact,
            'form' => $form->createView()
        ]);
    }

    #[Route('/delete/{id}', name: 'app_delete', methods: ['GET','POST'])]
    public function delete(Contact $contact) {
        $this->entityManager->remove($contact);
        $this->entityManager->flush();

        $this->redirectToRoute('app_homepage');
    }
}
