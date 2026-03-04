<?php

namespace ArnaldoTomo\AutoAlert\Drivers;

class AlertDriver extends AbstractDriver
{
    public function renderAlerts(array $alerts, string $layout, array $config): string
    {
        if (empty($alerts)) {
            return '';
        }
        
        $script = '<script>document.addEventListener("DOMContentLoaded", function() {';
        foreach ($alerts as $alert) {
            $msg = addslashes(strip_tags($alert['message']));
            $script .= "alert('{$msg}');\n";
        }
        $script .= '});</script>';
        
        return $script;
    }

    public function renderConfirmDelete(string $layout, array $config): string
    {
        return '<script>
        window.AutoAlert = window.AutoAlert || {};
        window.AutoAlert.confirmDelete = function(form) {
            if (confirm("Are you sure you want to delete this?")) {
                form.submit();
            }
        };
        </script>';
    }
}
