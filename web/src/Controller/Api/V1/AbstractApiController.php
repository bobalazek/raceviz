<?php

namespace App\Controller\Api\V1;

use App\Manager\ErrorManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
     * @var ErrorManager
     */
    protected $errorManager;

    public function __construct(
        ParameterBagInterface $params,
        EntityManagerInterface $em,
        UrlGeneratorInterface $router,
        ErrorManager $errorManager
    ) {
        $this->params = $params;
        $this->em = $em;
        $this->router = $router;
        $this->errorManager = $errorManager;
    }

    public function getFormErrors(FormInterface $form)
    {
        return $this->errorManager->getFormErrors($form);
    }
}
