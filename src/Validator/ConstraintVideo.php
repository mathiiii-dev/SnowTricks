<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ConstraintVideo extends Constraint
{
    public $videoMessage = 'Les liens donnés ne sont pas des vidéos Youtube valides !';
}
