<?php

namespace App\Exceptions;

use BadMethodCallException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ReflectionException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

trait ApiException
{
    /**
     * Handle all exceptions.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $exception
     */
    public function apiException($request, $exception)
    {
        if ($this->notModel($exception)) {
            return $this->notModelMessage($exception);
        }

        if ($this->notHttp($exception)) {
            return $this->notHttpMessage();
        }

        if ($this->notAuthorized($exception)) {
            return $this->notAuthorizedMessage($exception);
        }

        if ($this->notMethod($exception)) {
            return $this->notMethodMessage();
        }

        if ($this->notController($exception)) {
            return $this->notControllerMessage();
        }

        if ($this->badMethod($exception)) {
            return $this->badMethodMessage($exception);
        }

        return parent::render($request, $exception);
    }

    private function notModel($exception)
    {
        return $exception instanceof ModelNotFoundException;
    }

    private function notHttp($exception)
    {
        return $exception instanceof NotFoundHttpException;
    }

    private function notAuthorized($exception)
    {
        return $exception instanceof UnauthorizedHttpException;
    }

    private function notController($exception)
    {
        return $exception instanceof ReflectionException;
    }

    private function notMethod($exception)
    {
        return $exception instanceof MethodNotAllowedHttpException;
    }

    private function badMethod($exception)
    {
        return $exception instanceof BadMethodCallException;
    }

    public function notModelMessage($exception)
    {
        return response()->json(['success' => false, 'message' => 'Entry for '.str_replace('App\\', '', $exception->getModel()).' not found'], Response::HTTP_NOT_FOUND);
    }

    public function notHttpMessage()
    {
        return response()->json(['success' => false, 'message' => 'Invalid route'], Response::HTTP_NOT_FOUND);
    }

    public function modelNotFoundMessage($exception)
    {
        return response()->json(['success' => false, 'message' => $exception->getMessage()], Response::HTTP_NOT_FOUND);
    }

    public function notMethodMessage()
    {
        return response()->json(['success' => false, 'message' => 'Bad request method'], Response::HTTP_BAD_REQUEST);
    }

    public function notControllerMessage()
    {
        return response()->json(['success' => false, 'message' => 'Invalid controller'], Response::HTTP_BAD_REQUEST);
    }

    public function badMethodMessage($exception)
    {
        return response()->json(['success' => false, 'message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
    }

    public function notAuthorizedMessage($exception)
    {
        $getException = $exception->getPrevious();

        if ($getException instanceof TokenInvalidException) {
            return response()->json(['success' => false, 'message' => 'Invalid token'], Response::HTTP_UNAUTHORIZED);
        }

        if ($getException instanceof TokenExpiredException) {
            return response()->json(['success' => false, 'message' => 'Invalid token'], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json(['success' => false, 'message' => 'You are not authorized'], Response::HTTP_UNAUTHORIZED);
    }
}
