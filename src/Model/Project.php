<?php

declare(strict_types=1);

namespace App\Model;

class Project
{
    public function __construct(
        private readonly ?array $data,
    ) {
    }

    public function getData(): ?array
    {
        return $this->data;
    }

}
