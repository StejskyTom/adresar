<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use App\Services\StringService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

class HomepageController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ContactRepository $contactRepository,
    )
    {
    }

    #[Route('/', name: 'app_homepage')]
    public function index(Request $request ,PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $this->contactRepository->getAllQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('homepage/index.html.twig', [
            'contacts' => $pagination,
        ]);
    }

    #[Route('/edit/{identifier}', name: 'app_edit', methods: ['GET', 'POST'])]
    #[Route('/new', name: 'app_new', methods: ['GET', 'POST'])]
    public function newAction(Request $request, string $identifier = null): Response
    {
        if (!$identifier) {
            $contact = new Contact();
        } else {
            $contact = $this->contactRepository->getByIdentifier($identifier);
        }

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($contact);
            $this->entityManager->flush();

            $identifier = $contact->getSurname().'-'.$contact->getId();
            $identifier = StringService::getSluggedString($identifier);

            $contact->setIdentifier($identifier);

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

        return $this->redirectToRoute('app_homepage');
    }

    #[Route('/get-user-info', name: 'get_user_info')]
    public function getUserInfo(Request $request): Response
    {
        (int)$id = $request->get('id');

        /** @var Contact $contact */
        $contact = $this->contactRepository->find($id);

        $fullName = $contact->getName().' '.$contact->getSurname();

        return new JsonResponse(array('note' => $contact->getNote(), 'fullName' => $fullName ));
    }
}
