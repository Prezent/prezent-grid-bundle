<?php

declare(strict_types=1);

namespace Prezent\GridBundle\Tests\Fixtures\AppBundle\Grid\Type;

use Prezent\Grid\BaseGridType;
use Prezent\Grid\Extension\Core\Type;
use Prezent\Grid\GridBuilder;

/**
 * @author Sander Marechal
 */
class TestGridType extends BaseGridType
{
    /**
     * {@inheritDoc}
     */
    public function buildGrid(GridBuilder $builder, array $options = [])
    {
        $builder
            ->addColumn('id', Type\StringType::class, [
                'label' => 'grid.id',
                'route' => 'view',
                'route_parameters' => ['id' => '{id}'],
            ])
            ->addColumn('name', Type\StringType::class, [
                'label' => 'grid.name',
                'sortable' => true,
            ])
        ;
    }
}
