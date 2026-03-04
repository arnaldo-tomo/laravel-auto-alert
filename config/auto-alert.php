<?php

return [
    /*
     * Which notification driver to use.
     * Supported: 'toast', 'sweetalert', 'modal', 'alert'
     */
    'driver' => env('AUTO_ALERT_DRIVER', 'toast'),

    /*
     * Which CSS framework is used in the project.
     * Supported: 'bootstrap', 'tailwind'
     * If left as null, the install command will prompt for this choice.
     */
    'layout' => env('AUTO_ALERT_LAYOUT', null),

    /*
     * Position of the alerts (if the driver supports it, e.g., toast).
     * Supported: 'top-right', 'top-left', 'bottom-right', 'bottom-left', 'top-center', 'bottom-center'
     */
    'position' => env('AUTO_ALERT_POSITION', 'top-right'),

    /*
     * Auto-hide timeout in milliseconds.
     */
    'timeout' => env('AUTO_ALERT_TIMEOUT', 4000),

    /*
     * Whether to automatically intercept DELETE forms and show a confirmation.
     */
    'confirm_delete' => env('AUTO_ALERT_CONFIRM_DELETE', true),
];
