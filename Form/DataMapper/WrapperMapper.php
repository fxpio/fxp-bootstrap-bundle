<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\BootstrapBundle\Form\DataMapper;

use Symfony\Component\Form\DataMapperInterface;

/**
 * Lets have a compound form with a data mapper not doing any work by default.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class WrapperMapper implements DataMapperInterface
{
    /**
     * {@inheritdoc}
     */
    public function mapDataToForms($data, $forms)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function mapFormsToData($forms, &$data)
    {
    }
}
