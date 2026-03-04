<?php

namespace ArnaldoTomo\AutoAlert\Drivers;

class ToastDriver extends AbstractDriver
{
    public function renderAlerts(array $alerts, string $layout, array $config): string
    {
        if (empty($alerts)) {
            return '';
        }

        $isTailwind = $layout === 'tailwind';
        $positionCss = $this->getPositionCss($config['position']);

        $wrapperClass = $isTailwind 
            ? 'fixed z-[9999] flex flex-col gap-2 pointer-events-none' 
            : 'position-fixed z-3 d-flex flex-column gap-2 p-3 w-100' . ($config['position'] == 'top-right' ? ' top-0 end-0' : ''); 

        $html = '<div id="auto-alert-toast-container" class="' . $wrapperClass . '" style="' . $positionCss . ($isTailwind ? '' : ' max-width: 350px; pointer-events: none;') . '">';
        
        foreach ($alerts as $alert) {
            $html .= $this->getToastHtml($alert, $layout);
        }

        $html .= '</div>';

        $timeout = $config['timeout'];
        $html .= "<script>
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(function() {
                    const container = document.getElementById('auto-alert-toast-container');
                    if (container) {
                        container.style.transition = 'opacity 0.5s';
                        container.style.opacity = '0';
                        setTimeout(() => container.remove(), 500);
                    }
                }, {$timeout});
            });
        </script>";

        return $html;
    }

    public function renderConfirmDelete(string $layout, array $config): string
    {
        return (new AlertDriver())->renderConfirmDelete($layout, $config);
    }

    protected function getToastHtml(array $alert, string $layout): string
    {
        $type = $alert['type'];
        $message = htmlspecialchars($alert['message']);
        $isTailwind = $layout === 'tailwind';

        if ($isTailwind) {
            $colors = match ($type) {
                'error' => 'bg-red-500 text-white',
                'warning' => 'bg-yellow-500 text-white',
                'info' => 'bg-blue-500 text-white',
                default => 'bg-green-500 text-white',
            };
            return "<div class='px-4 py-3 rounded shadow-lg pointer-events-auto {$colors}'>{$message}</div>";
        } else {
            $colors = match ($type) {
                'error' => 'bg-danger text-white',
                'warning' => 'bg-warning text-dark',
                'info' => 'bg-info text-dark',
                default => 'bg-success text-white',
            };
            return "<div class='toast show align-items-center border-0 {$colors}' role='alert' aria-live='assertive' aria-atomic='true' style='pointer-events: auto;'>
                <div class='d-flex'>
                    <div class='toast-body'>{$message}</div>
                </div>
            </div>";
        }
    }
}
