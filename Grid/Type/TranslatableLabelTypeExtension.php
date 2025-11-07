<?php

declare(strict_types=1);

namespace Prezent\GridBundle\Grid\Type;

use Prezent\Grid\BaseElementTypeExtension;
use Prezent\Grid\ElementView;
use Prezent\Grid\Extension\Core\Type\ElementType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Translate labels
 *
 * @see BaseElementTypeExtension
 * @author Sander Marechal
 */
class TranslatableLabelTypeExtension extends BaseElementTypeExtension
{
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label_translation_domain' => null,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(ElementView $view, array $options): void
    {
        $view->vars['label_translation_domain'] = $options['label_translation_domain'] !== null
            ? $options['label_translation_domain']
            : $view->parent->vars['label_translation_domain'];
    }

    /**
     * {@inheritDoc}
     */
    public function getExtendedType(): string
    {
        return ElementType::class;
    }
}
