<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Yajra\Pdo\Oci8\Exceptions\Oci8Exception;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        // Exceptions especificas com tratamento especial
        $response = $this->handlerSpecialExceptions($exception);

        // Exceptions genéricas
        if (! $response) {
            $response = $this->handlerGenericExceptions($exception);
        }

        return $response;
    }

    protected function handlerSpecialExceptions(Throwable $e)
    {
        if ($e instanceof \Illuminate\Auth\AuthenticationException) {
            return response()->json(['error' => $e->getMessage()], 401);
        }

        if ($e instanceof CredentialsException) {
            return response()->json(['error' => 'Erro no login.', 'details' => 'Credenciais inválidas.'], 401);
        }

        if ($e instanceof CodeException) {
            return response()->json(['error' => 'Código inválido.', 'details' => 'Erro na validação do código'], 422);
        }

        if ($e instanceof UserException) {
            return response()->json(['error' => 'Código inválido.', 'details' => $e->getMessage()], 422);
        }

        if ($e instanceof ModelNotFoundException) {
            return response()->json(['error' => 'Recurso não encontrado.', 'details' => $e->getMessage()], 404);
        }

        if ($e instanceof ValidationException) {
            return $e->response;
        }

        if ($e instanceof QueryException) {
            if (strpos($e->getMessage(), 'not-null') !== false) {
                return response()->json(['error' => 'Todos os campos obrigatórios devem ser preenchidos.', 'details' => $e->getMessage()], 400);
            }

            if (strpos($e->getMessage(), 'foreign key') !== false) {
                return response()->json(['error' => 'Não foi possível remover o registro pois ele possui outros relacionamentos.', 'details' => $e->getMessage()], 400);
            }

            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                return response()->json(['error' => 'Já existe um recurso com essas informações.', 'details' => $e->getMessage()], 400);
            }

            if (strpos($e->getMessage(), 'restrição exclusiva') !== false) {
                return response()->json(['error' => 'Já existe um recurso com essas informações.', 'details' => $e->getMessage()], 405);
            } else {
                return response()->json(['error' => 'Exceção de consulta', 'details' => $e->getMessage()], 400);
            }
        }

        if ($e instanceof Oci8Exception) {
            if (strpos($e->getMessage(), 'not-null') !== false) {
                return response()->json(['error' => 'Todos os campos obrigatórios devem ser preenchidos.', 'details' => $e->getMessage()], 400);
            }

            if (strpos($e->getMessage(), 'unique constraint') !== false) {
                return response()->json(['error' => 'Já existe um recurso com essas informações.', 'details' => $e->getMessage()], 400);
            }

            if (strpos($e->getMessage(), 'restrição exclusiva') !== false) {
                return response()->json(['error' => 'Já existe um recurso com essas informações.', 'details' => $e->getMessage()], 405);
            } else {
                return response()->json(['error' => 'Exceção de Oci8', 'details' => $e->getMessage()], 400);
            }
        }
    }

    protected function handlerGenericExceptions(Throwable $e)
    {
        if ($e instanceof NotFoundHttpException) {
            return response()->json(['error' => 'Rota não encontrada!'], 404);
        }
        if ($e instanceof MethodNotAllowedHttpException) {
            return response()->json(['error' => 'Método não permitido!'], $e->getStatusCode());
        }
        if ($e instanceof \Exception) {
            return response()->json(['error' => 'Ocorreu um erro.', 'details' => $e->getMessage()], 500);
        }
    }
}
