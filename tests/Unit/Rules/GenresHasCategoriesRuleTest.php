<?php

namespace Tests\Unit\Rules;

use App\Rules\GenresHasCategoriesRule;
use Illuminate\Support\Collection;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class GenresHasCategoriesRuleTest extends TestCase
{

    protected function assertReflectionClass(MockInterface $rule, string $property) {
        $reflectionClass =  new \ReflectionClass(GenresHasCategoriesRule::class);
        $reflectionProperty = $reflectionClass->getProperty($property);
        $reflectionProperty->setAccessible(true);

        $propertyId = $reflectionProperty->getValue($rule);
        $this->assertEqualsCanonicalizing([1, 2], $propertyId);
    }

    protected function createRuleMock(array $array): MockInterface
    {
        return \Mockery::mock(GenresHasCategoriesRule::class, [$array])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
    }

    protected function mockWithProperties(array $values, string $method, array $args, Collection $return)
    {
        return $this->createRuleMock($values)->shouldReceive($method)
            ->withArgs($args)
            ->andReturn($return);
    }

    public function testCategoriesIdField()
    {
        $rule = $this->createRuleMock([1, 1, 2, 2]);
        $this->assertReflectionClass($rule, "categoriesId");
    }

    public function testeGenresIdField()
    {
        $rule = $this->mockWithProperties([], "getRows", null, collect());


        $rule->passes('', [1, 1, 2, 2]);

        $this->assertReflectionClass($rule, "genresId");
    }

    public function testPassesReturnsFalseWhenCategoriesOrGenresIsArrayEmpty()
    {
        $rule = $this->createRuleMock([1]);
        $this->assertFalse($rule->passes('', []));

        $rule = $this->createRuleMock([]);
        $this->assertFalse($rule->passes('', [1]));
    }

    public function testPassesReturnsFalseWhenGetRowsIsEmpty()
    {
        $rule = $this->mockWithProperties([1], "getRows", null, collect());
        $this->assertFalse($rule->passes('', [1]));
    }

    public function testPassesReturnsFalseWhenHasCategoriesWithoutGenres()
    {
        $rule = $this->mockWithProperties([1, 2], "getRows", null, collect(["category_id" => 1]));

        $this->assertFalse($rule->passes('', [1]));
    }

    public function testPassesIsValid()
    {
        $rule = $this->mockWithProperties([1, 2], "getRows", null, collect([
            ["category_id" => 1],
            ["category_id" => 2]
        ]));

        $this->assertTrue($rule->passes('', [1]));
    }

}
