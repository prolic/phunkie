<?php

namespace spec\Phunkie\Types;

use Phunkie\Cats\Show;
use function Phunkie\Functions\show\showValue;
use function Phunkie\Functions\show\usesTrait;
use Phunkie\Types\None;
use Phunkie\Types\Some;
use PhpSpec\ObjectBehavior;

use Phunkie\Ops\Option\OptionApplicativeOps;

use Eris\TestTrait;
use Eris\Generator\IntegerGenerator as IntGen;

/**
 * @mixin OptionApplicativeOps
 */
class OptionSpec extends ObjectBehavior
{
    use TestTrait;

    function let()
    {
        $this->beAnInstanceOf(Some::class);
        $this->beConstructedThrough('instance', [1]);
    }

    function it_is_showable()
    {
        $this->shouldBeShowable();
        expect(showValue(Option(2)))->toReturn("Some(2)");
        expect(showValue(None()))->toReturn("None");
    }

    function it_is_a_functor()
    {
        $spec = $this;
        $this->forAll(
            new IntGen()
        )->then(function($a) use ($spec) {
            expect(Option($a)->map(function ($x) {
                return $x + 1;
            }))->toBeLike(Some($a + 1));
        });
    }

    function it_returns_none_when_none_is_mapped()
    {
        $this->beAnInstanceOf(None::class);
        $this->beConstructedThrough('instance', []);

        $this->map(function($x) { return $x + 1; })->shouldBeLike(None());
    }

    function it_has_applicative_ops()
    {
        $this->shouldBeUsing(OptionApplicativeOps::class);
    }

    function it_returns_none_when_none_is_applied()
    {
        $this->beAnInstanceOf(None::class);
        $this->beConstructedThrough('instance', []);
        $this->apply(Option(function($x) { return $x + 1; }))->shouldBeLike(None());
    }

    function it_applies_the_result_of_the_function_to_a_List()
    {
        $spec = $this;
        $this->forAll(
            new IntGen()
        )->then(function($a) use ($spec) {
            expect(Option($a)->apply(Option(function($x) { return $x + 1; })))
                ->toBeLike(Some($a + 1));
        });
    }

    function getMatchers()
    {
        return [
            "beUsing" => function($sus, $trait){
                return usesTrait($sus, $trait);
            },
            "beShowable" => function($sus){
                return usesTrait($sus, Show::class);
            }
        ];
    }
}