<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

/**
 * Class ApplicationType.
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa070819999@gmail.com>
 */
class ApplicationType extends AbstractType
{
    /**
     * Get basic form configuration.
     *
     * @param string $label
     * @param string $placeholder
     * @param array $options
     * 
     * @return array
     */
    public function getConfiguration(string $label = "", string $placeholder = "", array $options = []): array
    {
        return array_merge_recursive([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ], $options);
    }
}
