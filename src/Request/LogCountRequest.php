<?php

namespace App\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LogCountRequest extends Request
{
    /**
     * @Assert\All({
     *     @Assert\Type("string"),
     * })
     */
    public ?array $serviceNames = null;

    /**
     * @Assert\Type("integer")
     */
    public ?int $statusCode = null;

    /**
     * @Assert\Date()
     */
    public ?string $startDate = null;

    /**
     * @Assert\Date()
     */
    public ?string $endDate = null;

    public function __construct(Request $request, ValidatorInterface $validator)
    {
        parent::__construct(
            $request->query->all(),
            $request->request->all(),
            $request->attributes->all(),
            $request->cookies->all(),
            $request->files->all(),
            $request->server->all(),
            $request->getContent()
        );

        $data = json_decode($this->getContent(), true);
        $this->serviceNames = $data['serviceNames'] ?? null;
        $this->statusCode = $data['statusCode'] ?? null;
        $this->startDate = $data['startDate'] ?? null;
        $this->endDate = $data['endDate'] ?? null;

        $this->validate($validator);
    }

    public function validate(ValidatorInterface $validator): void
    {
        $errors = $validator->validate($this);
        if (count($errors) > 0) {
            $this->throwValidationException($errors);
        }
    }

    protected function throwValidationException(ConstraintViolationListInterface $errors): void
    {
        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[] = $error->getPropertyPath() . ': ' . $error->getMessage();
        }
        throw new \InvalidArgumentException(json_encode($errorMessages));
    }
}
