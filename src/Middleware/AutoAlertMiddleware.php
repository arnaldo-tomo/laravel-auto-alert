<?php

namespace ArnaldoTomo\AutoAlert\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use ArnaldoTomo\AutoAlert\AlertManager;
use ArnaldoTomo\AutoAlert\LayoutManager;
use ArnaldoTomo\AutoAlert\ExceptionListener;
use ArnaldoTomo\AutoAlert\Drivers\ToastDriver;
use ArnaldoTomo\AutoAlert\Drivers\SweetAlertDriver;
use ArnaldoTomo\AutoAlert\Drivers\ModalDriver;
use ArnaldoTomo\AutoAlert\Drivers\AlertDriver;

class AutoAlertMiddleware
{
    protected $alertManager;
    protected $layoutManager;

    public function __construct(AlertManager $alertManager, LayoutManager $layoutManager)
    {
        $this->alertManager = $alertManager;
        $this->layoutManager = $layoutManager;
    }

    public function handle(Request $request, Closure $next)
    {
        /** @var Response $response */
        $response = $next($request);

        if (isset($response->exception) && $response->exception) {
            app(ExceptionListener::class)->handle($request, $response->exception);
        }

        if (!$this->isHtmlResponse($response)) {
            return $response;
        }

        $alerts = $this->alertManager->getAlerts($request);
        $config = config('auto-alert');
        $layout = $this->layoutManager->getLayout();
        
        if (empty($alerts) && !$config['confirm_delete']) {
            return $response;
        }

        $driverName = $config['driver'] ?? 'toast';
        $driver = $this->resolveDriver($driverName);

        $htmlInjection = '';

        if ($config['confirm_delete']) {
            $htmlInjection .= $driver->renderConfirmDelete($layout, $config);
        }

        if (!empty($alerts)) {
            $htmlInjection .= $driver->renderAlerts($alerts, $layout, $config);
        }

        $jsData = json_encode([
            'confirm_delete' => $config['confirm_delete']
        ]);

        $jsSource = file_get_contents(__DIR__ . '/../../resources/js/auto-alert.js');

        $injection = "
        <!-- Laravel Auto Alert -->
        {$htmlInjection}
        <script>
            window.AutoAlertConfig = {$jsData};
            {$jsSource}
        </script>
        <!-- End Laravel Auto Alert -->\n";

        $content = $response->getContent();
        $pos = strripos($content, '</body>');

        if ($pos !== false) {
            $content = substr_replace($content, $injection, $pos, 0);
            $response->setContent($content);
        }

        return $response;
    }

    protected function isHtmlResponse(Response $response): bool
    {
        $contentType = method_exists($response, 'headers') ? $response->headers->get('Content-Type') : '';
        return str_contains(strtolower((string)$contentType), 'text/html');
    }

    protected function resolveDriver(string $name)
    {
        return match ($name) {
            'sweetalert' => new SweetAlertDriver(),
            'modal' => new ModalDriver(),
            'alert' => new AlertDriver(),
            default => new ToastDriver(),
        };
    }
}
