<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Testing\TestResponse;
use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\PSR7\OperationAddress;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;

class TestServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('testing')) {
            $this->registerTestResponseMacros();
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    protected function registerTestResponseMacros()
    {
        TestResponse::macro('assertSchema', function () {
            $schema = test()->schema;
            $validator = (new ValidatorBuilder())
                ->fromYamlFile(base_path("docs/{$schema}"))
                ->getResponseValidator();

            $operation = new OperationAddress(
                '/' . request()->path(),
                strtolower(request()->method())
            );

            // convert TestResponse to PSR-7 response
            $psr17Factory = new Psr17Factory();
            $psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);
            $psrResponse = $psrHttpFactory->createResponse($this->baseResponse);

            try {
                $validator->validate($operation, $psrResponse);

                return $this;
            } catch (ValidationFailed $e) {
                $e = $e->getPrevious() ?? $e;
                test()->fail($e->getMessage());
            }
        });
    }
}
