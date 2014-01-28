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


use Symfony\Component\Console\Command\Command;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Yepsua\GeneratorBundle\Generator\DoctrineCrudGenerator as YepsuaDoctrineCrudGenerator;


/**
 * Generates a CRUD for a Doctrine entity.
 * It is based/extended from SensioGeneratorBundle
 * @author Omar Yepez <omar.yepez@yepsua.com>
 */
class GenerateDoctrineCrudCommand extends \Sensio\Bundle\GeneratorBundle\Command\GenerateDoctrineCrudCommand
{
    private $generator;
    private $input;

    /**
     * @see Command
     */
    protected function configure()
    {
      parent::configure();
      $this->addOption('layout', null , InputOption::VALUE_REQUIRED, 'The layout for the index','YepsuaGeneratorBundle::layout.html.twig');
      $this->addOption('multipart', null , InputOption::VALUE_NONE, 'Set true if the form enctype is multipart/form-data');
      $this->addOption('with-kanban', null , InputOption::VALUE_NONE, 'Enable the Kanban View');
      $this->setDescription('Generates a CRUD based on a Doctrine entity for a Rich Internet Aplication.')
           ->setHelp(<<<EOT
The <info>doctrine:generate:richcrud</info> command generates a CRUD based on a Doctrine entity for a Rich Internet Aplication. 

The default command only generates the list and show actions.

<info>php app/console doctrine:generate:richcrud --entity=AcmeBlogBundle:Post --route-prefix=post_admin</info>

Using the --with-write option allows to generate the new, edit and delete actions.

<info>php app/console doctrine:generate:richcrud --entity=AcmeBlogBundle:Post --route-prefix=post_admin --with-write</info>

Using the --layout option allows to change the default layout.

<info>php app/console doctrine:generate:richcrud --layout=AcmeBlogBundle::layout.html.twig</info>
               
Using the --multipart option allows to change the enctype form to multipart/form-data. This option affect the routes: UPDATE and DELETE.

<info>php app/console doctrine:generate:richcrud --multipart</info>
                   
Using the --with-kanban option allows to enable the Kanban View.

<info>php app/console doctrine:generate:richcrud --multipart</info>
EOT
            )
           ->setName('doctrine:generate:richcrud')
           ->setAliases(array('generate:doctrine:richcrud'))
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output){
        $this->input = $input;
        parent::execute($input, $output);
    }
    
    protected function createGenerator($bundle = null)
    {
        $generator =  new YepsuaDoctrineCrudGenerator($this->getContainer()->get('filesystem'));
        $generator->addOption('layout', $this->input->getOption('layout'));
        $generator->addOption('multipart', $this->input->getOption('multipart'));
        $generator->addOption('with_kanban', $this->input->getOption('with-kanban'));
        return $generator;
    }
    
    protected function getSkeletonDirs(BundleInterface $bundle = null)
    {
        $skeletonDirs = array();
        $skeletonDirs[] = __DIR__.'/../Resources/skeleton';
        $skeletonDirs[] = __DIR__.'/../Resources';
        return $skeletonDirs;
    }
}
