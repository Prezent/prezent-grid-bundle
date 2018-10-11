<?php

namespace Prezent\GridBundle\Grid\Type;

use Prezent\Grid\BaseElementTypeExtension;
use Prezent\Grid\ElementView;
use Prezent\Grid\Extension\Core\Type\ElementType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Translate values
 *
 * @see BaseElementTypeExtension
 * @author Sander Marechal
 */
class TranslatableValueTypeExtension extends BaseElementTypeExtension
{
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'value_translation_domain' => null,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(ElementView $view, array $options)
    {
        $view->vars['value_translation_domain'] = $options['value_translation_domain'];
    }

    /**
     * {@inheritDoc}
     */
    public function getExtendedType()
    {
        return ElementType::class;
    }
}
