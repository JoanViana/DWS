<?php

namespace DWSBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use DWSBundle\Entity\Product;
use DWSBundle\Entity\Category;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use DWSBundle\Service\FixturesService;


class LoadProductData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface 
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

        $fd = fopen($dir.'products.csv', "r");
        
        //$repository = $this->getDoctrine()
	    //    ->getRepository("DWSBundle:Category");
	    //$categories = $repository->findAll();
        //$service = new FixturesService();
        //$categories = $service->runCategories();
        $service = $this->container->get('dws.category_service');
        $categories = $service->getCategories();
        //$controller = $this->get('dws.category_controller');
        //$categories = $controller->getAllAction();
        $cat = array();
	    foreach($categories as $category){
	        $name = $category->getName();
	        $this->addReference($name, $category);
	    }
	    
        //$this->addReference('carnes', $cat);

        $row = 0;        
        
        $root = $this->container->getParameter('kernel.root_dir');
        $dir = str_replace('/app', '/src/DWSBundle/Resources/data/', $root);
        
        if (($fd = fopen($dir."products.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($fd, 1000, ",")) !== FALSE) {
                $row++;
                if ($row == 1) continue; //skip header
                $product = new Product();
                $product->setName($data[0]);
                $product->setDescription($data[1]);
                $product->setPrice($data[3]);
                $product->setCategory($this->getReference($data[2]));

                $manager->persist($product);
                $manager->flush();
            }
            fclose($fd);
        } 
    }
    
    public function getOrder()
    {
        return 2;
    }
}