<?php

namespace ArnaldoTomo\AutoAlert;

class LayoutManager
{
    public function getLayout(): string
    {
        return config('auto-alert.layout') ?? 'tailwind';
    }

    public function isBootstrap(): bool
    {
        return $this->getLayout() === 'bootstrap';
    }

    public function isTailwind(): bool
    {
        return $this->getLayout() === 'tailwind';
    }
}
