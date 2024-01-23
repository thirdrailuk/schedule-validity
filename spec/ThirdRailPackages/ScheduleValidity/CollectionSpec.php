<?php

namespace spec\ThirdRailPackages\ScheduleValidity;

use PhpSpec\ObjectBehavior;
use ThirdRailPackages\ScheduleValidity\Collection;

class CollectionSpec extends ObjectBehavior
{
    private $train_class_data = [
        'Pendolino',
        'HST',
        'Pacer',
        'A4 Pacific',
        'Hoover'
    ];

    function let()
    {
        $this->beConstructedWith($this->train_class_data);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Collection::class);
    }

    function it_has_constructed_data()
    {
        $this->all()->shouldBe($this->train_class_data);
    }

    function it_can_be_construced_through_make()
    {
        $this->beConstructedThroughMake($this->train_class_data);
        $this->all()->shouldBe($this->train_class_data);
    }

    function it_can_count_items()
    {
        $this->shouldImplement(\Countable::class);
        $this->shouldHaveCount(5);
    }

    function it_is_an_iterator()
    {
        $this->shouldImplement(\IteratorAggregate::class);
        $this->shouldIterateAs(new \ArrayIterator($this->train_class_data));
    }

    function it_has_array_features()
    {
        $this->shouldImplement(\ArrayAccess::class);

        $this[0]->shouldBe($this->train_class_data[0]); // Pendolino
        $this[4]->shouldBe($this->train_class_data[4]); // Hoover
    }

    function it_can_apply_a_function_to_each_item()
    {
        $callback = function($item, $key) {
            return sprintf(
                'Tran class "%s" with index "%d"',
                $item,
                $key
            );
        };

        $this->each($callback);
    }

    function it_can_map_items()
    {
        $data = [
            [
                'brand_name' => "Pendolino",
                'toc'        => "Avanti West Coast"
            ],
            [
                'brand_name' => "Azuma",
                'toc'        => "LNER"
            ],
            [
                'brand_name' => "Nova1",
                'toc'        => "TransPennine Express"
            ]
        ];

        $this->beConstructedWith($data);

        $function = function ($item) {
            return $item['brand_name'];
        };

        $expectedCollection = Collection::make([
            "Pendolino",
            "Azuma",
            "Nova1"
        ]);

        $this->map($function)->shouldBeLike($expectedCollection);
    }

    function it_can_filter_items()
    {
        $data = [
            [
                'br_class' => '220',
                'toc'      => 'Avanti West Coast'
            ],
            [
                'br_class' => '220',
                'toc'      => 'CrossCountry'
            ],
            [
                'br_class' => '390',
                'toc'      => 'Avanti West Coast'
            ],
            [
                'br_class' => '802',
                'toc'      => 'Great Western Railway'
            ]
        ];

        $this->beConstructedWith($data);

        $function = function ($item) {
            return $item['br_class'] === '220';
        };

        $expectedCollection = Collection::make([
            [
                'br_class' => '220',
                'toc'      => 'Avanti West Coast'
            ],
            [
                'br_class' => '220',
                'toc'      => 'CrossCountry'
            ]
        ]);

        $this->filter($function)->shouldBeLike($expectedCollection);
    }

    function it_can_reject_items()
    {
        $data = ['C', 'N', 'O', 'P'];

        $this->beConstructedWith($data);

        $function = function ($item) {
            return $item !== 'N';
        };

        $this->reject($function)
            ->toArray()
            ->shouldBeLike([1 => 'N']);
    }

    function it_has_empty_data_set()
    {
        $this->beConstructedWith([]);

        $this->isEmpty()->shouldBe(true);
    }

    function it_can_sort_items()
    {
        $this->beConstructedWith(['C', 'V', 'A', 'M']);

        $this->sort()->values()->toArray()->shouldBeLike(['A', 'C', 'M', 'V']);
    }
}
