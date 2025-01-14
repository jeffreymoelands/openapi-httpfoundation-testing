<?php

declare(strict_types=1);

namespace Osteel\OpenApi\Testing\Tests\Request;

use InvalidArgumentException;
use Osteel\OpenApi\Testing\Request\Adapters\RequestAdapterInterface;
use Osteel\OpenApi\Testing\Request\RequestValidator;
use Osteel\OpenApi\Testing\Request\RequestValidatorBuilder;
use Osteel\OpenApi\Testing\Tests\TestCase;

class RequestValidatorBuilderTest extends TestCase
{
    public function definitionProvider(): array
    {
        return [
            ['fromYaml', self::$yamlDefinition],
            ['fromYaml', file_get_contents(self::$yamlDefinition)],
            ['fromJson', self::$jsonDefinition],
            ['fromJson', file_get_contents(self::$jsonDefinition)],
        ];
    }

    /**
     * @dataProvider definitionProvider
     */
    public function testItBuildsARequestValidator(string $method, string $definition)
    {
        $result = RequestValidatorBuilder::$method($definition)->getValidator();

        $this->assertInstanceOf(RequestValidator::class, $result);

        // Validate a request to make sure the definition was correctly parsed.
        $this->assertTrue($result->validate($this->httpFoundationRequest('/test', 'get', ['foo' => 'bar'])));
    }

    public function testItDoesNotSetTheAdapterBecauseItsTypeIsInvalid()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            'Class %s does not implement the %s interface',
            InvalidArgumentException::class,
            RequestAdapterInterface::class
        ));

        RequestValidatorBuilder::fromYaml(self::$yamlDefinition)
            ->setAdapter(InvalidArgumentException::class);
    }

    public function testItSetsTheAdapter()
    {
        RequestValidatorBuilder::fromYaml(self::$yamlDefinition)
            ->setAdapter(get_class($this->createMock(RequestAdapterInterface::class)));

        // No exception means the test was successful.
        $this->assertTrue(true);
    }
}
