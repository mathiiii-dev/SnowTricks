<?php

namespace App\Validator;

use App\Services\UrlService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ConstraintVideoValidator extends ConstraintValidator
{
    private $urlCheck;
    public function __construct(UrlService $urlCheck)
    {
        $this->urlCheck = $urlCheck;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ConstraintVideo) {
            throw new UnexpectedTypeException($constraint, ConstraintVideo::class);
        }

        if (!$this->urlCheck->checkVideoUrl($value)) {
            $this->context->buildViolation($constraint->videoMessage)
                ->addViolation();
        }
    }
}