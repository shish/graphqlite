<?php


namespace TheCodingMachine\GraphQLite\Mappers\Parameters;


use GraphQL\Type\Definition\InputType;
use GraphQL\Type\Definition\NamedType;
use GraphQL\Type\Definition\OutputType;
use function is_array;
use function iterator_to_array;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Type;
use ReflectionMethod;
use ReflectionParameter;
use TheCodingMachine\GraphQLite\Mappers\Parameters\ParameterMapperInterface;
use TheCodingMachine\GraphQLite\Parameters\ParameterInterface;

class CompositeParameterMapper implements ParameterMapperInterface
{
    /**
     * @var ParameterMapperInterface[]
     */
    private $parameterMappers;

    /**
     * @param ParameterMapperInterface[] $parameterMappers
     */
    public function __construct(iterable $parameterMappers)
    {
        $this->parameterMappers = is_array($parameterMappers) ? $parameterMappers : iterator_to_array($parameterMappers);
    }

    /**
     * @param array<string, DocBlock\Tags\Param> $paramTags
     */
    public function mapParameter(ReflectionParameter $parameter, DocBlock $docBlock, array $paramTags): ?ParameterInterface
    {
        foreach ($this->parameterMappers as $parameterMapper) {
            $parameterObj = $parameterMapper->mapParameter($parameter, $docBlock, $paramTags);
            if ($parameterObj !== null) {
                return $parameterObj;
            }
        }
        return null;
    }
}