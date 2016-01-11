<?php

namespace DWSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use DWSBundle\Entity\Product;
use DWSBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends Controller
{
	public static $id_default = 1;
	public static $name_default = "Pan de Pueblo";
	public static $price_default = 00.85;
	public static $description_default = "Barra de pan de pueblo cocida a leña de 250 gr";
	
	private function initCategory(){
	
		$repository = $this->getDoctrine()
		->getRepository("DWSBundle:Category");
		
		$category = $repository->findOneByName(CategoryController::$name_default);

		if(!$category){
			$category = new Category();
			$category->setName(CategoryController::$name_default);
			
			$validator = $this->get("validator");
			$errors = $validator->validate($category);
			
			if (count($errors) > 0) {

				$errorsString = (string) $errors;
			
				return $this->render("DWSBundle::index.html.twig", array("text" => $errorsString));

			}
				
				
			$em = $this->getDoctrine()->getManager();
			$em->persist($category);
			$em->flush();
		}
		
		return $category;
	}
	/*
	private function initProduct(){
		
		$repository = $this->getDoctrine()
		->getRepository("DWSBundle:Product");
		
		$product = $repository->findOneByName(self::$name_default);
		
		if(!$product){
			$product = new Product();
			$product->setName(self::$name_default);
			$product->setPrice(self::$price_default);
			$product->setDescription(self::$description_default);
			$product->setCategory($this->initCategory());
			
			$validator = $this->get("validator");
			
			$errors = $validator->validate($product);
			
			if (count($errors) > 0) {

				$errorsString = (string) $errors;
			
				return $this->render("DWSBundle::index.html.twig", array("text" => $errorsString));
			}
			
			$this->addAction($product);
		}	
		
		return $product;
	}
	*/
	
	
	/**
 	* @Security("has_role('ROLE_APP_ADMIN')")
 	*/
    public function createStaticAction()
    {
    	$category = $this->initCategory();
    	
    	$product = new Product();
    	$product->setName(self::$name_default);
    	$product->setPrice(self::$price_default);
    	$product->setDescription(self::$description_default);
    	$product->setCategory($category);
    	
    	$validator = $this->get("validator");
    	$errors = $validator->validate($product);
    	
    	if (count($errors) > 0) {

    		$errorsString = (string) $errors;
    	
    		return $this->render("DWSBundle::index.html.twig", array("text" => $errorsString));

    	}
    	 
    	
    	$this->addAction($product);
    	
    	//return new Response("Default product added. Id: ".$product->getId());
    	
    	$text = "Default product added. Id: ".$product->getId();
    	//return new Response("Default product added. Id: ".$product->getId());
    	
    	$repository = $this->getDoctrine()
    	->getRepository("DWSBundle:Product");
    	
    	$products = $repository->findAll();
    		
    	return $this->render("DWSBundle:Product:list.html.twig", array("text" => $text,
    			"products" => $products));
    }
    
    /**
 	* @Security("has_role('ROLE_APP_ADMIN')")
 	*/
    public function createParamAction($name,$price)
    {
    	$category = $this->initCategory();
    
    	$product = new Product();
    	$product->setName($name);
    	$product->setPrice($price);
    	$product->setDescription("NULL - ".$name);
    	$product->setCategory($category);
    	
    	$validator = $this->get("validator");
    	$errors = $validator->validate($product);
    	
    	if (count($errors) > 0) {

    		$errorsString = (string) $errors;
    	
    		return $this->render("DWSBundle::index.html.twig", array("text" => $errorsString));

    	}
    	 
    	
    	$this->addAction($product);
    		
    	return new Response("Created product:
								\n Id: ".$product->getId().
    			"\n Category: ".$product->getCategory()->getName().
    			"\n	Name: ".$product->getName().
    			"\n	Price: ".$product->getPrice().
    			"\n Description: ".$product->getDescription()."\n\n");
    		
    	//return $this->render("DWSBundle::index.html.twig", array("name" => $name));
    
    }

	/**
 	* @Security("has_role('ROLE_APP_ADMIN'||'ROLE_USER')")
 	*/
	public function showAction($id)
	{
	    $product = $this->getDoctrine()
	        ->getRepository("DWSBundle:Product")
	        ->find($id);
	
	    if (!$product) {
// 	        throw $this->createNotFoundException(
// 	            "No product found for id ".$id
// 	        );
// 	        return new Response("There is not any product with id: ".$id);
	         
	        $text = "There is not any product with id: ".$id;
			return $this->render("DWSBundle::index.html.twig", array("text" => $text));
	    }
	
// 	    return new Response(
// 	    		" Id: ".$product->getId().
// 	    		"\n Category: ".$product->getCategory()->getName().
// 	    		"\n Name: ".$product->getName().
// 	    		"\n Price: ".$product->getPrice().
// 	    		"\n Description: ".$product->getDescription());
    	
		return $this->render("DWSBundle:Product:show.html.twig", array("product" => $product));
	}
	
	/**
 	* @Security("has_role('ROLE_APP_ADMIN'||'ROLE_USER')")
 	*/
	public function listAction()
	{
		$repository = $this->getDoctrine()
	        ->getRepository("DWSBundle:Product");
	    
	    $products = $repository->findAll();
	
		if (count($products) === 0) {
// 			throw $this->createNotFoundException(
// 					"Not found");
// 			return new Response("There is not any product");
	
	        $text = "There is not any product";
			return $this->render("DWSBundle::index.html.twig", array("text" => $text));		}
		
// 		$list = "";
		
// 		foreach($products as $product){
			
// 			$list .= "Id: ".$product->getId().
// 				"\n Category: ".$product->getCategory()->getName().
// 				"\n Name: ".$product->getName().
// 				"\n Price: ".$product->getPrice().
// 				"\n Description: ".$product->getDescription()."\n\n";
// 		}
		
// 		return new Response($list);

		return $this->render("DWSBundle:Product:list.html.twig", array("products" => $products
		));
		 
	}
	
	/**
 	* @Security("has_role('ROLE_APP_ADMIN'||'ROLE_USER')")
 	*/
	public function listByCategoryAction($category)
	{
		$repository = $this->getDoctrine()
		->getRepository("DWSBundle:Category");
		
		$cat = $repository->findOneByName($category);
			
		if (count($cat) === 0) {
// 			throw $this->createNotFoundException(
// 					"Not found");
// 			return new Response("There is not any product named ".$category);
	
			$text = "There is not any product with id: ".$id;
			return $this->render("DWSBundle::index.html.twig", array("text" => $text));
		}
		
		$products = $cat->getProducts();
		
// 		$list = "";
	
// 		foreach($products as $product){
				
// 			$list .= "Id: ".$product->getId().
// 			"\n Category: ".$product->getCategory()->getName().
// 			"\n Name: ".$product->getName().
// 			"\n Price: ".$product->getPrice().
// 			"\n Description: ".$product->getDescription()."\n\n";
// 		}
	
// 		return new Response($list);
			
		return $this->render("DWSBundle:Product:listbycat.html.twig", array("category" => $cat
		));
	}
	
	/**
 	* @Security("has_role('ROLE_APP_ADMIN'||'ROLE_USER')")
 	*/
	public function listAllByCategoryAction()
	{
		$repository = $this->getDoctrine()
			->getRepository("DWSBundle:Category");
	
		$categories = $repository->findAll();
			
		if (count($categories) === 0) {
// 			throw $this->createNotFoundException(
// 					"Not found");
// 			return new Response("There is not any category");
	
	        $text = "There is not any product";
			return $this->render("DWSBundle::index.html.twig", array("text" => $text));		
		}
		
// 		$list = "";
		
// 		foreach($categories as $category){
				
// 			$list .= "Category \n Id: ".$category->getId().
// 			"\n Name: ".$category->getName()."\n";
			
// 			$products = $category->getProducts();			
		
// 			foreach($products as $product){
		
// 				$list .= "\Product Id: ".$product->getId().
// 				"\n Category: ".$product->getCategory()->getName().
// 				"\n Name: ".$product->getName().
// 				"\n Price: ".$product->getPrice().
// 				"\n Description: ".$product->getDescription()."\n\n";
// 			}
			
// 			$list .= "\n\n\n";
// 		}
		
// 		return new Response($list);

		
		return $this->render("DWSBundle:Product:listallbycat.html.twig", array("categories" => $categories
		));
	}
	
	/**
 	* @Security("has_role('ROLE_APP_ADMIN')")
 	*/	
	public function deleteAction($id)
	{
		$product = $this->getDoctrine()
			->getRepository("DWSBundle:Product")
			->find($id);
	
		if (!$product) {
// 			throw $this->createNotFoundException(
// 					"No product found for id ".$id
// 					);
// 			return new Response("There is not any product with id: ".$id);
	
	        $text = "There is not any product with id: ".$id;
			return $this->render("DWSBundle::index.html.twig", array("text" => $text));		}
		
		$this->removeAction($product);
	
		$text = "Product ".$product->getName()." with Id ". $id." removed";
		//return new Response("Product ".$product->getName()." with Id ". $id." removed");
		
		$repository = $this->getDoctrine()
			->getRepository("DWSBundle:Product");
		
		$products = $repository->findAll();
		 
		return $this->render("DWSBundle:Product:list.html.twig", array("text" => $text,
																			"products" => $products
		));
	}
	
	/**
 	* @Security("has_role('ROLE_APP_ADMIN')")
 	*/
	public function newProductAction(Request $request) {
		
		$product = new Product();
	
		$form = $this->createFormBuilder($product)
			->add("name", "text", array(
					//"placeholder" 	=> "Pan Bimbo Familiar",
					"required"    	=> true,
					"empty_data"  	=> null))
			->add("category","entity",array(
					"class" => "DWSBundle:Category",
					"choice_label" => "name",
					//"placeholder" 	=> "Alimentacion",
					"required"    	=> true,
					"empty_data"  	=> null))
			->add("price", "money", array(
					"currency"		=> "EUR",
					//"placeholder" 	=> 01.99,
					"scale"			=> 2,
					"required"    	=> true,
					"empty_data"  	=> null))
			->add("description", "textarea", array(
					//"placeholder" 	=> "Brief description",
					"required"    	=> false))
    		->add('save', 'submit', array('label' => 'Create Product'))
    		->add('saveAndAdd', 'submit', array('label' => 'Save and Add'))
	
			->getForm();
		
		$form->handleRequest($request);
		
		if ($request->isMethod("POST") && $form->isValid()) {
			$this->addAction($product);	
			
			$continueAction = $form->get('saveAndAdd')->isClicked();
				
			if($continueAction){
				$text = "Product ".$product->getName()." with Id ". $product->getId()." added";
				return $this->render("DWSBundle:Product:new.html.twig", array(
						"form" => $form->createView(),"text" => $text,
				));
			}
			
			//return new Response("Product ".$product->getName()." with Id ". $product->getId()." added");
			$text = "Product ".$product->getName()." with Id ". $product->getId()." added";
			return $this->render("DWSBundle::index.html.twig", array("text" => $text, "success" => true));
		}
	
		return $this->render("DWSBundle:Product:new.html.twig", array(
				"form" => $form->createView()
				));
	}
	
	/**
 	* @Security("has_role('ROLE_APP_ADMIN')")
 	*/	
	public function editAction($id, Request $request) {
		
		$product = $this->searchByIdAction($id);
	
		$form = $this->createFormBuilder($product)
			->add("name", "text", array(
					//"placeholder" 	=> "Pan Bimbo Familiar",
					"required"    	=> true,
					"empty_data"  	=> null,
					"data"			=> $product->getName()))
			->add("category","entity",array(
					"class" => "DWSBundle:Category",
					"choice_label" => "name",
					//"placeholder" 	=> "Alimentacion",
					"required"    	=> true,
					"empty_data"  	=> null,
					"data"			=> $product->getCategory()))
			->add("price", "money", array(
					"currency"		=> "EUR",
					//"placeholder" 	=> 01.99,
					"scale"			=> 2,
					"required"    	=> true,
					"empty_data"  	=> null,
					"data"			=> $product->getPrice()))
			->add("description", "textarea", array(
					//"placeholder" 	=> "Brief description",
					"required"    	=> false,
					"data"			=> $product->getDescription()))
    		->add('save', 'submit', array('label' => 'Update Product'))
	
			->getForm();
		
		$form->handleRequest($request);
		
		if ($request->isMethod("POST") && $form->isValid()) {
			
			$this->updateAction();
			
			//return new Response("Product ".$product->getName()." with Id ". $product->getId()." added");
			$text = "Product ".$product->getName()." with Id ". $product->getId()." updated";
			$products = $this->searchAllAction();
			return $this->render("DWSBundle:Product:list.html.twig", array("text" => $text,
				"products" => $products));
		}
	
		return $this->render("DWSBundle:Product:edit.html.twig", array(
				"form" => $form->createView(),
				"product" => $product
		));
	}
	
	private function addAction($product) {
		
		$em = $this->getDoctrine()->getManager();		
		$em->persist($product);
		$em->flush();
	}
	
	private function removeAction($product) {
	
		$em = $this->getDoctrine()->getManager();
		$em->remove($product);
		$em->flush();
	}
	
	private function updateAction() {
	
		$em = $this->getDoctrine()->getManager();
		$em->flush();
	}
	
	private function searchByIdAction($id) {
	
    	return $this->getDoctrine()
	    	->getRepository("DWSBundle:Product")
	    	->find($id);
	}
	
	private function searchAllAction() {
	
		$repository = $this->getDoctrine()
			->getRepository("DWSBundle:Product");
		
		return $repository->findAll();
	}
	/*
	private function searchByNameAction($name) {
	
		$repository = $this->getDoctrine()
			->getRepository("DWSBundle:Product");
		
		return $repository->findOneByName(self::$name_default);
	}
	*/

}