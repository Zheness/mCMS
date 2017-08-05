<?php

namespace Mcms\Modules\Admin\Forms;

use Phalcon\Forms\Form;

class FormBase extends Form
{
    const FR_VALIDATOR_PRESENCE_OF = "Une valeur est obligatoire";
    const FR_VALIDATOR_EMAIL = "La valeur n'est pas une addresse email valide";
    const FR_VALIDATOR_NOT_IN_DOMAIN = "La valeur séléctionnée ne fait pas partie des valeurs autorisées";
    private $csrfValid = true;
    private $csrfCheck = true;

    /**
     * @return bool
     */
    public function isCsrfValid(): bool
    {
        return $this->csrfValid;
    }

    /**
     * @param bool $csrfValid
     */
    public function setCsrfValid(bool $csrfValid)
    {
        $this->csrfValid = $csrfValid;
    }

    /**
     * @return bool
     */
    public function hasCsrfCheck(): bool
    {
        return $this->csrfCheck;
    }

    /**
     * @param bool $csrfCheck
     */
    public function setCsrfCheck(bool $csrfCheck)
    {
        $this->csrfCheck = $csrfCheck;
    }

    /**
     * Validates the form
     *
     * @param array $data
     * @param object $entity
     * @return bool
     */
    public function isValid($data = null, $entity = null)
    {
        if ($this->hasCsrfCheck()) {
            if (!$this->security->checkToken()) {
                $this->setCsrfValid(false);
                return false;
            }
        }
        return parent::isValid($data, $entity);
    }
}
