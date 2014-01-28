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

use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

/**
 * Generates a CRUD controller.
 * 
 * @author Omar Yepez <omar.yepez@yepsua.com>
 */
class DoctrineCrudGenerator extends \Sensio\Bundle\GeneratorBundle\Generator\DoctrineCrudGenerator
{
    protected $options; 
    
    
    protected function renderFile($template, $target, $parameters)
    {
       $parameters = array_merge($parameters, array(
         'consoleOptions' => $this->getOptions(),
         'fields' => $this->metadata->fieldMappings, 
         'associationMappings' => $this->metadata->associationMappings)
       );
       parent::renderFile($template, $target, $parameters);
    }
    
    /**
     * Generate the CRUD controller.
     *
     * @param BundleInterface   $bundle           A bundle object
     * @param string            $entity           The entity relative class name
     * @param ClassMetadataInfo $metadata         The entity class metadata
     * @param string            $format           The configuration format (xml, yaml, annotation)
     * @param string            $routePrefix      The route name prefix
     * @param array             $needWriteActions Wether or not to generate write actions
     *
     * @throws \RuntimeException
     */
    public function generate(BundleInterface $bundle, $entity, ClassMetadataInfo $metadata, $format, $routePrefix, $needWriteActions, $forceOverwrite)
    {
        parent::generate($bundle, $entity, $metadata, $format, $routePrefix, $needWriteActions, $forceOverwrite);
        if($this->getOption('with_kanban')){
            $dir = sprintf('%s/Resources/views/%s', $this->bundle->getPath(), str_replace('\\', '/', $this->entity));
            $this->generateKanbanView($dir);
        }

    }
    
    /**
     * Generates the show.html.twig template in the final bundle.
     *
     * @param string $dir The path to the folder that hosts templates in the bundle
     */
    protected function generateKanbanView($dir)
    {
        $this->renderFile('crud/views/kanban.html.twig.twig', $dir.'/kanban.html.twig', array(
            'bundle'            => $this->bundle->getName(),
            'entity'            => $this->entity,
            'fields'            => $this->metadata->fieldMappings,
            'actions'           => $this->actions,
            'route_prefix'      => $this->routePrefix,
            'route_name_prefix' => $this->routeNamePrefix,
        ));
    }
    
    
    
    public function getOptions() {
        return $this->options;
    }
    
    public function getOption($key) {
        return $this->options[$key];
    }
    
    public function addOption($key, $value) {
        return $this->options[$key] = $value;
    }

    public function setOptions($options) {
        $this->options = $options;
    }
}
