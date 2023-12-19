<?php

namespace App\Exceptions;

use App\Models\System\General\Routs;
use App\Models\System\General\Template;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpKernel\EventListener\ResponseListener;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
   public function render($request, Throwable $exception){
            $list = ['404'];
            if (method_exists($exception,'getStatusCode')) {
                    $codeHTTP = $exception->getStatusCode();
                    if (in_array($codeHTTP, $list)) {

                        $params = ['byname' => (string)$codeHTTP];
                        $route = new Routs($params, [DB::raw( 'Template.body_view')]);
                        $route->setJoin(['Template']);
                        $route_error = $route->getOne();
                        if (!empty($route_error)) {
                            $route_error['body_view'] = Template::removeAnchorComponent($route_error['body_view']);
                            return Response::make(Blade::render(Template::buildTemplateFromBody($route_error)),$codeHTTP);

                        }
                    }
                }
           return parent::render($request, $exception);
    }
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
