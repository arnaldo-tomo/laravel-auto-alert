<?php

namespace ArnaldoTomo\AutoAlert\Drivers;

class ModalDriver extends AbstractDriver
{
    public function renderAlerts(array $alerts, string $layout, array $config): string
    {
        if (empty($alerts)) {
            return '';
        }

        $type = count($alerts) === 1 ? $alerts[0]['type'] : 'error';
        $messageHtml = implode('<br>', array_map(function($a) { return htmlspecialchars($a['message']); }, $alerts));
        
        $isTailwind = $layout === 'tailwind';

        if ($isTailwind) {
            $colors = match ($type) {
                'error' => 'text-red-600',
                'warning' => 'text-yellow-600',
                'info' => 'text-blue-600',
                default => 'text-green-600',
            };
            
            $html = "
            <div id='auto-alert-modal' class='fixed inset-0 z-[9999] flex items-center justify-center overflow-y-auto overflow-x-hidden bg-black bg-opacity-50'>
                <div class='relative w-full max-w-md p-4 bg-white rounded-lg shadow sm:p-5'>
                    <div class='flex justify-between mb-4 rounded-t sm:mb-5'>
                        <h3 class='text-lg font-semibold {$colors}'>Notice</h3>
                        <button type='button' onclick='document.getElementById(\"auto-alert-modal\").remove()' class='text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center'>
                            <svg class='w-5 h-5' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z' clip-rule='evenodd'></path></svg>  
                        </button>
                    </div>
                    <p class='mb-4 text-gray-700'>{$messageHtml}</p>
                    <div class='flex justify-end'>
                        <button onclick='document.getElementById(\"auto-alert-modal\").remove()' class='text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center'>Okay</button>
                    </div>
                </div>
            </div>";
        } else {
            // Bootstrap
            $colors = match ($type) {
                'error' => 'text-danger',
                'warning' => 'text-warning',
                'info' => 'text-info',
                default => 'text-success',
            };

            $html = "
            <div id='auto-alert-modal' class='modal show d-block' tabindex='-1' style='background-color: rgba(0,0,0,0.5); z-index: 9999;'>
                <div class='modal-dialog modal-dialog-centered'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='modal-title {$colors}'>Notice</h5>
                            <button type='button' class='btn-close' onclick='document.getElementById(\"auto-alert-modal\").remove()'></button>
                        </div>
                        <div class='modal-body'>
                            <p>{$messageHtml}</p>
                        </div>
                        <div class='modal-footer'>
                            <button type='button' class='btn btn-primary' onclick='document.getElementById(\"auto-alert-modal\").remove()'>Okay</button>
                        </div>
                    </div>
                </div>
            </div>";
        }

        return $html;
    }

    public function renderConfirmDelete(string $layout, array $config): string
    {
        return (new AlertDriver())->renderConfirmDelete($layout, $config);
    }
}
