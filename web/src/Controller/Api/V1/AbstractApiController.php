<?php

namespace App\Controller\Api\V1;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class AbstractApiController.
 */
class AbstractApiController extends AbstractController
{
    /**
     * @var ParameterBagInterface
     */
    protected $params;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var UrlGeneratorInterface
     */
    protected $router;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    public function __construct(
        ParameterBagInterface $params,
        EntityManagerInterface $em,
        UrlGeneratorInterface $router,
        TranslatorInterface $translator
    ) {
        $this->params = $params;
        $this->em = $em;
        $this->router = $router;
        $this->translator = $translator;
    }

    public function getFormErrors(FormInterface $form)
    {
        $errors = [];

        foreach ($form->getErrors() as $error) {
            if ($form->getParent()) {
                $errors[] = $error->getMessage();
            } else {
                if (!isset($errors['*'])) {
                    $errors['*'] = [];
                }

                $errors['*'][] = $error->getMessage();
            }
        }

        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getFormErrors($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }

        return $errors;
    }
}