<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
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
        return $this->handlerSpecialExceptions($exception)
            ?? $this->handlerGenericExceptions($exception)
            ?? parent::render($request, $exception);
    }

    /**
     * `details` só com a mensagem interna quando APP_DEBUG=true — nunca em produção.
     */
    private function debug(Throwable $e): array
    {
        return config('app.debug') ? ['details' => $e->getMessage()] : [];
    }

    protected function handlerSpecialExceptions(Throwable $e)
    {
        // FormRequest::failedValidation lança isso com a resposta 422 pronta.
        if ($e instanceof HttpResponseException) {
            return $e->getResponse();
        }

        if ($e instanceof AuthenticationException) {
            return response()->json(['error' => 'Não autenticado.'], 401);
        }

        // Sanctum MissingAbilityException estende AuthorizationException.
        if ($e instanceof AuthorizationException) {
            return response()->json(['error' => 'Acesso negado.', 'details' => 'Você não tem permissão para esta ação.'], 403);
        }

        if ($e instanceof ThrottleRequestsException || $e instanceof TooManyRequestsHttpException) {
            return response()->json(['error' => 'Muitas requisições. Tente novamente em instantes.'], 429);
        }

        if ($e instanceof CredentialsException) {
            return response()->json(['error' => 'Erro no login.', 'details' => 'Credenciais inválidas.'], 401);
        }

        if ($e instanceof CodeException) {
            return response()->json(['error' => 'Código inválido.', 'details' => 'Erro na validação do código'], 422);
        }

        if ($e instanceof UserException) {
            return response()->json(['error' => $e->getMessage()], $e->statusCode ?? 422);
        }

        if ($e instanceof ModelNotFoundException) {
            return response()->json(['error' => 'Recurso não encontrado.'], 404);
        }

        if ($e instanceof ValidationException) {
            return $e->response
                ?? response()->json(['error' => 'Erro no envio de dados.', 'details' => $e->errors()], $e->status);
        }

        if ($e instanceof QueryException || $e instanceof Oci8Exception) {
            return $this->handlerDatabaseException($e);
        }

        return null;
    }

    protected function handlerDatabaseException(Throwable $e)
    {
        $msg = $e->getMessage();

        if (str_contains($msg, 'not-null') || str_contains($msg, 'NOT NULL')) {
            return response()->json(['error' => 'Todos os campos obrigatórios devem ser preenchidos.'], 400);
        }

        if (str_contains($msg, 'foreign key') || str_contains($msg, 'FOREIGN KEY')) {
            return response()->json(['error' => 'Não foi possível concluir: o registro possui outros vínculos.'], 409);
        }

        if (str_contains($msg, 'Duplicate entry') || str_contains($msg, 'UNIQUE') || str_contains($msg, 'unique constraint') || str_contains($msg, 'restrição exclusiva')) {
            return response()->json(['error' => 'Já existe um recurso com essas informações.'], 409);
        }

        report($e);

        return response()->json(array_merge(['error' => 'Erro ao acessar os dados.'], $this->debug($e)), 400);
    }

    protected function handlerGenericExceptions(Throwable $e)
    {
        if ($e instanceof NotFoundHttpException) {
            return response()->json(['error' => 'Rota não encontrada!'], 404);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return response()->json(['error' => 'Método não permitido!'], $e->getStatusCode());
        }

        if ($e instanceof HttpExceptionInterface) {
            return response()->json(['error' => $e->getMessage() ?: 'Erro na requisição.'], $e->getStatusCode());
        }

        report($e);

        return response()->json(array_merge(['error' => 'Ocorreu um erro.'], $this->debug($e)), 500);
    }
}
