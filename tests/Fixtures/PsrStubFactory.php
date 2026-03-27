<?php declare(strict_types = 1);

namespace Tests\Fixtures;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

final class PsrStubFactory
{

	public static function createHttpClient(): ClientInterface
	{
		return new class implements ClientInterface {

			public function sendRequest(RequestInterface $request): ResponseInterface
			{
				throw new \RuntimeException('Stub');
			}

		};
	}

	public static function createRequestFactory(): RequestFactoryInterface
	{
		return new class implements RequestFactoryInterface {

			public function createRequest(string $method, $uri): RequestInterface // phpcs:ignore SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingAnyTypeHint
			{
				throw new \RuntimeException('Stub');
			}

		};
	}

	public static function createStreamFactory(): StreamFactoryInterface
	{
		return new class implements StreamFactoryInterface {

			public function createStream(string $content = ''): StreamInterface
			{
				throw new \RuntimeException('Stub');
			}

			public function createStreamFromFile(string $filename, string $mode = 'r'): StreamInterface
			{
				throw new \RuntimeException('Stub');
			}

			public function createStreamFromResource($resource): StreamInterface // phpcs:ignore SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingAnyTypeHint
			{
				throw new \RuntimeException('Stub');
			}

		};
	}

}
