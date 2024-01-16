<?php

declare(strict_types=1);

namespace App\Form\Model;

use App\Utils\Form\FormErrors;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use JsonSerializable;
use OpenApi\Attributes as SWG;

#[SWG\Schema(
    title: 'Abstract form model',
    required: ['formName', 'fields', 'errors', 'submitted', 'valid', 'success'],
    type: 'object',
)]
class FormModel implements JsonSerializable
{
    #[SWG\Property(
        description: 'Form name',
        type: 'string',
        example: 'form'
    )]
    #[Groups(['swagger'])]
    public string $formName;

    #[SWG\Property(
        description: 'Form fields',
        type: 'array',
        items: new SWG\Items(type: 'string')
    )]
    #[Groups(['swagger'])]
    public array $fields;

    #[SWG\Property(
        description: 'Form errors',
        type: 'array',
        items: new SWG\Items(type: 'string')
    )]
    #[Groups(['swagger'])]
    public FormErrors $errors;

    #[SWG\Property(
        description: 'Form is submitted',
        type: 'boolean',
        example: true
    )]
    #[Groups(['swagger'])]
    public bool $submitted;

    #[SWG\Property(
        description: 'Form is valid',
        type: 'boolean',
        example: true
    )]
    #[Groups(['swagger'])]
    public ?bool $valid;

    #[SWG\Property(
        description: 'Request success',
        type: 'boolean',
        example: true
    )]
    #[Groups(['swagger'])]
    public ?bool $success;

    public function __construct(
        FormInterface $form
    ) {
        $this->formName = $form->getName();
        $this->fields = $this->buildFields($form);
        $this->errors = new FormErrors($form);
        $this->submitted = $form->isSubmitted();
        $this->valid = $form->isSubmitted() ? $form->isValid() : null;
        $this->success = $this->submitted && $this->valid;
    }

    private function buildFields(FormInterface $form): array
    {
        $fields = [];

        foreach ($form->all() as $child) {
            $fields[] = $child->getName();
        }

        return $fields;
    }

    public function jsonSerialize(): array
    {
        return [
            'formName' => $this->formName,
            'fields' => $this->fields,
            'errors' => $this->errors,
            'submitted' => $this->submitted,
            'valid' => $this->valid,
            'success' => $this->success,
        ];
    }
}
