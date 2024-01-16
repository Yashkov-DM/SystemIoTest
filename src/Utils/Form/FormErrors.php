<?php

declare(strict_types=1);

namespace App\Utils\Form;

use Symfony\Component\Form\FormInterface;

class FormErrors implements \JsonSerializable, \IteratorAggregate, \Stringable
{
    private array $errors;

    public function __construct(
        FormInterface $form,
        private readonly bool $flat = false
    ) {
        $this->errors = $this->buildErrors($form);
    }

    public function setErrors(array $errors): self
    {
        $this->errors = $errors;

        return $this;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    private function buildErrors(FormInterface $form): array
    {
        $errors = [];

        foreach ($form->all() as $child) {
            if ($childErrors = $this->buildErrors($child)) {
                if ($this->flat) {
                    $errors[$child->getName()] = $childErrors;
                } else {
                    $errors += $childErrors;
                }
            }
        }

        foreach ($form->getErrors() as $error) {
            if ($this->flat) {
                $errors[] = $error->getMessage();
            } else {
                $errors[$error->getOrigin()->getName()][] = $error->getMessage();
            }
        }

        return $errors;
    }

    public function jsonSerialize(): array
    {
        return $this->errors;
    }

    public function getIterator(): \Traversable
    {
        foreach ($this->errors as $key => $error) {
            yield $key => $error;
        }
    }

    public function __toString(): string
    {
        return \implode('<br/>', $this->getErrorTexts());
    }

    /**
     * @return string[]
     */
    public function getErrorTexts(): array
    {
        return \iterator_to_array($this->extractTexts($this->errors));
    }

    /**
     * @return \Generator<string>
     */
    private function extractTexts(array $errors): \Generator
    {
        foreach ($errors as $error) {
            if (\is_array($error)) {
                foreach ($this->extractTexts($error) as $text) {
                    yield $text;
                }
            } elseif (\is_string($error)) {
                yield $error;
            }
        }
    }

    public function hasErrors(): bool
    {
        return [] !== $this->errors;
    }
}
