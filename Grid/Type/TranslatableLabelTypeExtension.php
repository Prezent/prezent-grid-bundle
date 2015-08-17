<?php

namespace Prezent\GridBundle\Grid\Type;

use Prezent\Grid\BaseColumnTypeExtension;
use Prezent\Grid\ColumnView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Translate header labels
 *
 * @see BaseColumnTypeExtension
 * @author Sander Marechal
 */
class TranslatableLabelTypeExtension extends BaseColumnTypeExtension
{
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'label_translation_domain' => 'messages',
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(ColumnView $view, array $options)
    {
        $view->vars['label_translation_domain'] = $options['label_translation_domain'];
    }

    /**
     * {@inheritDoc}
     */
    public function getExtendedType()
    {
        return 'column';
    }
}
