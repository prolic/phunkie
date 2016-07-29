<?php

namespace {
    use Md\Phunkie\Types\Cons;
    use Md\Phunkie\Types\ImmList;
    use Md\Phunkie\Types\Nil;
    use Md\Phunkie\Types\NonEmptyList;

    function ImmList(...$values): ImmList { switch(count($values)) {
        case 0: return Nil();
        case 1: return new Cons($values[0], Nil());
        default: return new Cons($values[0], ImmList(...array_slice($values, 1))); }
    }

    function Nil(): ImmList
    {
        return new Nil();
    }

    function Cons($head, $tail)
    {
        if ($head == Nil) $head = Nil();
        if ($tail == Nil) $tail = Nil();
        return new Cons($head, $tail);
    }

    function Nel(...$values): NonEmptyList
    {
        return new NonEmptyList(...$values);
    }
}

namespace Md\Phunkie\Functions\immlist {

    use Md\Phunkie\Types\ImmList;

    function head(ImmList $list)
    {
        return $list->head();
    }

    function init(ImmList $list): ImmList
    {
        return $list->init();
    }

    function tail(ImmList $list): ImmList
    {
        return $list->tail();
    }

    function last(ImmList $list)
    {
        return $list->last();
    }

    function reverse(ImmList $list): ImmList
    {
        return $list->reverse();
    }

    function length(ImmList $list): int
    {
        return $list->length;
    }

    function concat(...$items): ImmList
    {
        $result = [];
        foreach ($items as $item) {
            $result = !$item instanceof ImmList ? array_merge($result, [$item]) : array_merge($result, $item->toArray());
        }
        return ImmList(...$result);
    }
}