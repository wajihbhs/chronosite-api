<?php

namespace App\Exception;

class NotFoundException extends \RuntimeException
{
    public function __construct(
        private readonly string $translationKey,
        private readonly array $translationParams = [],
    ) {
        parent::__construct($translationKey);
    }

    public function getTranslationKey(): string   { return $this->translationKey; }
    public function getTranslationParams(): array { return $this->translationParams; }
}
