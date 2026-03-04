<?php

namespace ArnaldoTomo\AutoAlert\Drivers;

class SweetAlertDriver extends AbstractDriver
{
    public function renderAlerts(array $alerts, string $layout, array $config): string
    {
        $html = '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        
        if (empty($alerts)) {
            return $html;
        }

        $html .= '<script>document.addEventListener("DOMContentLoaded", function() {';
        foreach ($alerts as $alert) {
            $type = $this->mapType($alert['type']);
            $text = addslashes(strip_tags($alert['message']));
            $timeout = $config['timeout'];
            $html .= "Swal.fire({
                icon: '{$type}',
                title: '{$text}',
                timer: {$timeout},
                timerProgressBar: true,
                showConfirmButton: false,
                toast: true,
                position: '{$this->mapPosition($config['position'])}'
            });\n";
        }
        $html .= '});</script>';
        
        return $html;
    }

    public function renderConfirmDelete(string $layout, array $config): string
    {
        return '<script>
        window.AutoAlert = window.AutoAlert || {};
        window.AutoAlert.confirmDelete = function(form) {
            if (typeof Swal === "undefined") {
                if (confirm("Are you sure?")) form.submit();
                return;
            }
            Swal.fire({
                title: "Are you sure?",
                text: "You will not be able to recover this data!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        };
        </script>';
    }

    protected function mapType(string $type): string
    {
        return match ($type) {
            'error' => 'error',
            'warning' => 'warning',
            'info' => 'info',
            default => 'success',
        };
    }

    protected function mapPosition(string $position): string
    {
        return match ($position) {
            'top-left' => 'top-start',
            'top-center' => 'top',
            'bottom-right' => 'bottom-end',
            'bottom-left' => 'bottom-start',
            'bottom-center' => 'bottom',
            default => 'top-end',
        };
    }
}
