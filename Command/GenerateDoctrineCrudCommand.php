<?php

/*
 * This file is part of the YepsuaGeneratorBundle.
 *
 * (c) Omar Yepez <omar.yepez@yepsua.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yepsua\GeneratorBundle\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Command\Command;
use Sensio\Bundle\GeneratorBundle\Generator\DoctrineCrudGenerator;

use Sensio\Bundle\GeneratorBundle\Generator\DoctrineFormGenerator;
use Sensio\Bundle\GeneratorBundle\Command\Helper\DialogHelper;
use Sensio\Bundle\GeneratorBundle\Manipulator\RoutingManipulator;

use Yepsua\GeneratorBundle\Generator\DoctrineCrudGenerator as YepsuaDoctrineCrudGenerator;


/**
 * Generates a CRUD for a Doctrine entity.
 * It is based/extended from SensioGeneratorBundle
 * @author Omar Yepez <omar.yepez@yepsua.com>
 */
class GenerateDoctrineCrudCommand extends \Sensio\Bundle\GeneratorBundle\Command\GenerateDoctrineCrudCommand
{
    private $generator;
    private $formGenerator;

    /**
     * @see Command
     */
    protected function configure()
    {
      parent::configure();
      
      $this->setDescription('Generates a CRUD based on a Doctrine entity for a Rich Internet Aplication.')
           ->setHelp(<<<EOT
The <info>doctrine:generate:richcrud</info> command generates a CRUD based on a Doctrine entity for a Rich Internet Aplication. 

The default command only generates the list and show actions.

<info>php app/console doctrine:generate:richcrud --entity=AcmeBlogBundle:Post --route-prefix=post_admin</info>

Using the --with-write option allows to generate the new, edit and delete actions.

<info>php app/console doctrine:generate:richcrud --entity=AcmeBlogBundle:Post --route-prefix=post_admin --with-write</info>
EOT
            )
           ->setName('doctrine:generate:richcrud')
           ->setAliases(array('generate:doctrine:richcrud'))
        ;
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
       parent::execute($input, $output);
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output);
    }

    /**
     * Tries to generate forms if they don't exist yet and if we need write operations on entities.
     */
    private function generateForm($bundle, $entity, $metadata)
    {
        parent::generateForm($bundle, $entity, $metadata);
    }

    private function updateRouting($dialog, InputInterface $input, OutputInterface $output, $bundle, $format, $entity, $prefix)
    {
        return parent::updateRouting($dialog, $input, $output, $bundle, $format, $entity, $prefix);
    }

    protected function getRoutePrefix(InputInterface $input, $entity)
    {
        return parent::getRoutePrefix($input, $entity);
    }

    protected function getGenerator()
    {
        parent::getGenerator();
        if (null === $this->generator) {
            $this->generator = new YepsuaDoctrineCrudGenerator($this->getContainer()->get('filesystem'), __DIR__.'/../Resources/skeleton/crud');
        }
        return $this->generator;
    }

    public function setGenerator(DoctrineCrudGenerator $generator)
    {
        parent::setGenerator($generator);
    }

    protected function getFormGenerator()
    {
       return parent::getFormGenerator();
    }

    public function setFormGenerator(DoctrineFormGenerator $formGenerator)
    {
       parent::setFormGenerator();
    }

    protected function getDialogHelper()
    {
        return parent::getDialogHelper();
    }
}
