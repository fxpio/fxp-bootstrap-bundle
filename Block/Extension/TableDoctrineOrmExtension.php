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
use Sonatra\Bundle\BootstrapBundle\Block\Type\TableType;
use Sonatra\Bundle\BootstrapBundle\Doctrine\ORM\Block\DataSource\DoctrineOrmDataSource;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            $source = new DoctrineOrmDataSource($this->entityManager, $options['row_id']);
            $source->setPageSizeMax($options['page_size_max']);
            $source->setPageSize($options['page_size']);
            $source->setQuery($data);
            $source->setLocale($options['locale']);
            $source->setSortColumns($options['sort_columns']);
            $source->setParameters($options['data_parameters']);
            $source->setPageNumber($options['page_number']);

            $builder->setData($source);
            $builder->setDataClass(get_class($source));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->addAllowedTypes('data', array('Doctrine\ORM\Query', 'Doctrine\ORM\QueryBuilder'));
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return TableType::class;
    }
}
