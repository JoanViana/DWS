<?php
namespace DWSBundle\Service;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;
//use DWSBundle\Entity\Product;
use DWSBundle\Entity\Category;

class FixturesService
{
    /** @var EntityManager */
    protected $entityManager;

    public function __construct($entityManager) {
        $this->entityManager = $entityManager;
    }
   
    public function getCategories()
    {
        $repository = $this->entityManager
            ->getRepository("DWSBundle:Category");
	    return $repository->findAll();
    }
    
    public function getName()
    {
        return 'dws.category_service';
    }
}