<?php

namespace Prezent\GridBundle\Tests\Fixtures\AppBundle\Controller;

use Prezent\GridBundle\Tests\Fixtures\AppBundle\Grid\Type\TestGridType;
use Prezent\Grid\GridFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @author Sander Marechal
 */
class TestController extends AbstractController
{
    public function index(GridFactory $factory)
    {
        $grid = $factory->createGrid(TestGridType::class);

        return $this->render('@App/index.html.twig', [
            'grid' => $grid->createView(),
            'data' => [
                (object) ['id' => 1, 'name' => 'foo'],
                (object) ['id' => 2, 'name' => 'bar'],
            ],
        ]);
    }

    public function view($id)
    {
        return $this->render('@App/view.html.twig', [
            'id' => $id,
        ]);
    }
}
