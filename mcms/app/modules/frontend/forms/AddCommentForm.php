<?php

namespace Mcms\Modules\Frontend\Forms;

use Mcms\Models\Option;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\ReCaptcha;
use Phalcon\Validation\Validator\StringLength;

class AddCommentForm extends FormBase
{
    public function initialize($entity = null, $options = [])
    {
        if (isset($options['connected']) && !$options['connected']) {
            $this->add($this->username());
        }
        $this->add($this->content());
        if (Option::findFirstBySlug('google_recaptcha_enabled_for_comments')->content == 'true') {
            $this->add($this->reCaptcha());
        }
    }

    private function username()
    {
        $element = new Text("username");
        $element->setLabel("Nom");
        $element->addValidator(new PresenceOf([
            "message" => self::FR_VALIDATOR_PRESENCE_OF
        ]));
        $element->addValidator(new StringLength([
            "max" => 60,
            "maxMessage" => "Votre nom ne peut dépasser les 60 caractères."
        ]));
        return $element;
    }

    private function content()
    {
        $element = new TextArea("content");
        $element->setLabel("Message");
        $element->addValidator(new PresenceOf([
            "message" => self::FR_VALIDATOR_PRESENCE_OF
        ]));
        return $element;
    }

    private function reCaptcha()
    {
        $element = new Text("g-recaptcha-response");
        $element->setLabel("reCAPTCHA");
        $element->addValidators([
            new ReCaptcha([
                'message' => 'Le captcha de sécurité est invalide',
                'secret' => Option::findFirstBySlug('google_recaptcha_secret')->content,
            ]),
        ]);
        return $element;
    }
}
