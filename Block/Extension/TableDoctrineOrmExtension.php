<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\BootstrapBundle\Block\Extension;

use Sonatra\Bundle\BlockBundle\Block\AbstractTypeExtension;
use Sonatra\Bundle\BlockBundle\Block\BlockBuilderInterface;
use Sonatra\Bundle\BootstrapBundle\Doctrine\ORM\Block\DataSource\DoctrineOrmDataSource;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Table Doctrine ORM Block Extension.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class TableDoctrineOrmExtension extends AbstractTypeExtension
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * Constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildBlock(BlockBuilderInterface $builder, array $options)
    {
        $data = $builder->getData();

        if ($data instanceof QueryBuilder) {
            $data = $data->getQuery();
        }

        if ($data instanceof Query) {
            $source = new DoctrineOrmDataSource($this->entityManager);
            $source->setQuery($data);
            $source->setLocale($options['locale']);
            $source->setPageSize($options['page_size']);
            $source->setStart($options['page_start']);
            $source->setPageNumber($options['page_number']);
            $source->setSortColumns($options['sort_columns']);
            $source->setParameters($options['data_parameters']);

            $builder->setData($source);
            $builder->setDataClass(get_class($source));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->addAllowedTypes(array(
            'data' => array('Doctrine\ORM\Query', 'Doctrine\ORM\QueryBuilder')
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'table';
    }
}
