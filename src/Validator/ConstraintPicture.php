<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ConstraintPicture extends Constraint
{
    public $pictureMessage = 'Les liens données ne sont pas des images !';
    public $emptyMessage = 'Au moins une image est nécessaire !';
}
