<?php

namespace Prezent\GridBundle\Tests\Fixtures\AppBundle\Controller;

use Prezent\GridBundle\Tests\Fixtures\AppBundle\Grid\Type\TestGridType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @author Sander Marechal
 */
class TestController extends Controller
{
    public function indexAction()
    {
        $grid = $this->get('grid_factory')->createGrid(TestGridType::class);

        return $this->render('@App/index.html.twig', [
            'grid' => $grid->createView(),
            'data' => [
                (object) ['id' => 1, 'name' => 'foo'],
                (object) ['id' => 2, 'name' => 'bar'],
            ],
        ]);
    }

    public function viewAction($id)
    {
        return $this->render('@App/view.html.twig', [
            'id' => $id,
        ]);
    }
}
