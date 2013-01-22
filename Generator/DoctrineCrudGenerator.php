<?php

/*
 * This file is part of the YepsuaGeneratorBundle.
 *
 * (c) Omar Yepez <omar.yepez@yepsua.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yepsua\GeneratorBundle\Generator;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

/**
 * Generates a CRUD controller.
 * 
 * @author Omar Yepez <omar.yepez@yepsua.com>
 */
class DoctrineCrudGenerator extends \Sensio\Bundle\GeneratorBundle\Generator\DoctrineCrudGenerator
{
    /**
     * Constructor.
     *
     * @param Filesystem $filesystem  A Filesystem instance
     * @param string     $skeletonDir Path to the skeleton directory
     */
    public function __construct(Filesystem $filesystem, $skeletonDir)
    {
      parent::__construct($filesystem, $skeletonDir);
    }
    
    protected function renderFile($skeletonDir, $template, $target, $parameters)
    {
       $parameters = array_merge($parameters, array('fields' => $this->metadata->fieldMappings));
       parent::renderFile($skeletonDir, $template, $target, $parameters);
    }
}
