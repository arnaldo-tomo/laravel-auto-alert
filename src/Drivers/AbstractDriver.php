<?php

namespace ArnaldoTomo\AutoAlert\Drivers;

abstract class AbstractDriver
{
    abstract public function renderAlerts(array $alerts, string $layout, array $config): string;
    
    abstract public function renderConfirmDelete(string $layout, array $config): string;

    protected function getPositionCss(string $position): string
    {
        return match ($position) {
            'top-left' => 'top: 1rem; left: 1rem;',
            'top-center' => 'top: 1rem; left: 50%; transform: translateX(-50%);',
            'bottom-right' => 'bottom: 1rem; right: 1rem;',
            'bottom-left' => 'bottom: 1rem; left: 1rem;',
            'bottom-center' => 'bottom: 1rem; left: 50%; transform: translateX(-50%);',
            default => 'top: 1rem; right: 1rem;',
        };
    }
}
