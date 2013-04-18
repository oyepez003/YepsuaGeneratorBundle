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

use Yepsua\GeneratorBundle\Generator\DoctrineCrudGenerator as YepsuaDoctrineCrudGenerator;


/**
 * Generates a CRUD for a Doctrine entity.
 * It is based/extended from SensioGeneratorBundle
 * @author Omar Yepez <omar.yepez@yepsua.com>
 */
class GenerateDoctrineCrudCommand extends \Sensio\Bundle\GeneratorBundle\Command\GenerateDoctrineCrudCommand
{
    private $generator;

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

    protected function getGenerator()
    {
        parent::getGenerator();
        if (null === $this->generator) {
            $this->generator = new YepsuaDoctrineCrudGenerator($this->getContainer()->get('filesystem'), __DIR__.'/../Resources/skeleton/crud');
        }
        return $this->generator;
    }
}
