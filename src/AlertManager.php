<?php

namespace ArnaldoTomo\AutoAlert;

use Illuminate\Http\Request;

class AlertManager
{
    public function getAlerts(Request $request): array
    {
        $alerts = [];
        $session = $request->session();

        if (!$session) {
            return $alerts;
        }

        $types = ['success', 'error', 'warning', 'info'];
        foreach ($types as $type) {
            if ($session->has($type)) {
                $message = $session->get($type);
                if (is_array($message)) {
                    foreach ($message as $msg) {
                        $alerts[] = ['type' => $type, 'message' => $msg];
                    }
                } else {
                    $alerts[] = ['type' => $type, 'message' => $message];
                }
            }
        }

        // Validation Errors
        $errors = $session->get('errors');
        if ($errors) {
            foreach ($errors->all() as $error) {
                $alerts[] = [
                    'type' => 'error',
                    'message' => $error
                ];
            }
        }

        return $alerts;
    }
}
