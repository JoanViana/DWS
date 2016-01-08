<?php

namespace DWSBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use DWSBundle\Entity\Category;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class LoadCategoryData implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null) {
        
         $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $root = $this->container->getParameter('kernel.root_dir');
        $dir = str_replace('/app', '/src/DWSBundle/Resources/data/', $root);
        
        $fd = fopen($dir.'categories.csv', "r");
        if ($fd) {
            while (($data = fgetcsv($fd)) !== false) {
                $category = new Category();
                $category->setName($data[0]);
                
                $manager->persist($category);
                $manager->flush();
            }            
            fclose($fd);
        }
    }
    
    public function getOrder()
    {
        return 1;
    }
}