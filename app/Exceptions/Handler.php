<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($this->isApiCall($request) && $request->input('api', 'true') == 'true') {
            if (method_exists($exception, 'getCode')) {
                $response['status_code'] = $exception->getCode();
            } else {
                $response['status_code'] = 500;
            }
            if ($exception instanceof \Illuminate\Validation\ValidationException) {
                $messages     = $exception->errors();

                $new_messages = [];

                foreach (array_values($messages) as $key => $message) {
                    $new_messages = array_merge($new_messages, $message);
                }

                $response['message']    = implode(', ', $new_messages);
                $response['message']    = $new_messages[0];
                $response['status_code'] = 400;
               
                /*$response['messages']    = $messages;
                $response['status_code'] = 400;*/
            } else {
                $response['message'] = $exception->getMessage();
                if (env('APP_DEBUG', true)) {
                    $response['trace'] = $exception->getTrace();
                }
            }

            if ($response['status_code'] <= 100 || $response['status_code'] >= 600) {
                $response['status_code'] = 500;
            }

            return response()->json($response, $response['status_code']);
        }
        return parent::render($request, $exception);
    }

    protected function isApiCall($request)
    {
        return strpos($request->getUri(), '/api/') !== false;
    }
}
