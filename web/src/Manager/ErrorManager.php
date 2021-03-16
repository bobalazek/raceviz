<?php

namespace App\Manager;

use Symfony\Component\Form\FormInterface;

/**
 * Class ErrorManager.
 */
class ErrorManager
{
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
