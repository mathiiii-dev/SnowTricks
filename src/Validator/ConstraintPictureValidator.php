<?php

namespace App\Validator;

use App\Services\UrlService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ConstraintPictureValidator extends ConstraintValidator
{
    private $urlCheck;
    public function __construct(UrlService $urlCheck)
    {
        $this->urlCheck = $urlCheck;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ConstraintPicture) {
            throw new UnexpectedTypeException($constraint, ConstraintPicture::class);
        }

        if($value->getValues() === []) {
            $this->context->buildViolation($constraint->emptyMessage)
                ->addViolation();
        }

        if (!$this->urlCheck->checkImageUrl($value)) {
            $this->context->buildViolation($constraint->pictureMessage)
                ->addViolation();
        }
    }
}