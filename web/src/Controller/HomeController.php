<?php

namespace App\Controller;

use App\Form\Type\ContactFormType;
use App\Manager\EmailManager;
use App\Manager\UserActionManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class HomeController.
 */
class HomeController extends AbstractController
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var UserActionManager
     */
    private $userActionManager;

    /**
     * @var EmailManager
     */
    private $emailManager;

    public function __construct(
        TranslatorInterface $translator,
        UserActionManager $userActionManager,
        EmailManager $emailManager
    ) {
        $this->translator = $translator;
        $this->userActionManager = $userActionManager;
        $this->emailManager = $emailManager;
    }

    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('contents/home/index.html.twig');
    }

    /**
     * @Route("/privacy", name="privacy")
     */
    public function privacy()
    {
        return $this->render('contents/home/privacy.html.twig');
    }

    /**
     * @Route("/terms", name="terms")
     */
    public function terms()
    {
        return $this->render('contents/home/terms.html.twig');
    }

    /**
     * @Route("/about", name="about")
     */
    public function about()
    {
        return $this->render('contents/home/about.html.twig');
    }

    /**
     * @Route("/help", name="help")
     */
    public function help()
    {
        return $this->render('contents/home/help.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request)
    {
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $this->emailManager->sendContact($data);

            $this->userActionManager->add(
                'contact',
                'A user has sent a contact message',
                $data
            );

            $this->addFlash(
                'success',
                $this->translator->trans('home.contact.flash.success')
            );

            return $this->redirectToRoute('contact');
        }

        return $this->render('contents/home/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
