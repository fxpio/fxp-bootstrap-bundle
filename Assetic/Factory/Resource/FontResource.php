<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\BootstrapBundle\Assetic\Factory\Resource;

use Assetic\Factory\Resource\CoalescingDirectoryResource;
use Assetic\Factory\Resource\DirectoryResource;

/**
 * Font resource.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class FontResource extends CoalescingDirectoryResource
{
    /**
     * Constructor.
     *
     * @param array $paths
     */
    public function __construct(array $paths)
    {
        $directories = array();

        foreach ($paths as $path) {
            if (is_dir($path)) {
                $directories[] = new DirectoryResource($path);
            }
        }

        parent::__construct($directories);
    }
}
