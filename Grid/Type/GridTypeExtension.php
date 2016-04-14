<?php

namespace Prezent\GridBundle\Grid\Type;

use Prezent\Grid\BaseGridTypeExtension;
use Prezent\Grid\GridView;
use Prezent\Grid\Extension\Core\GridType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * GridTypeExtension
 *
 * @see BaseGridTypeExtension
 * @author Sander Marechal
 */
class GridTypeExtension extends BaseGridTypeExtension
{
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label_translation_domain' => 'messages',
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(GridView $view, array $options)
    {
        $view->vars['label_translation_domain'] = $options['label_translation_domain'];
    }

    /**
     * {@inheritDoc}
     */
    public function getExtendedType()
    {
        return GridType::class;
    }
}
