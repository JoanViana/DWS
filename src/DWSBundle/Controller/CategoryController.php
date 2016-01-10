<?php

namespace DWSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use DWSBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
	public static $id_default = 1;
	public static $name_default = "Alimentacion";
	/*
	private function initCategory(){
		
		$category = $this->getDoctrine()
			->getRepository("DWSBundle:Category")
			->find(CategoryController::$id_default);
		
		if(!$category){
			$category = new Category();
			$category->setName(CategoryController::$name_default);
			
			$validator = $this->get("validator");
			$errors = $validator->validate($category);
			
			if (count($errors) > 0) {

				$errorsString = (string) $errors;
			
				return $this->render("DWSBundle::index.html.twig", array("text" => $errorsString));

			}
				
				
			$this->addAction($category);

		}
		
		return $category;
	}
	*/
	
	
	/**
 	* @Security("has_role('ROLE_ADMIN')")
 	*/
	public function createStaticAction()
	{
		$category = new Category();
		$category->setName(self::$name_default);
		 
		$validator = $this->get("validator");
		$errors = $validator->validate($category);
		 
		if (count($errors) > 0) {

			$errorsString = (string) $errors;
			 
			return $this->render("DWSBundle::index.html.twig", array("text" => $errorsString));

		}
	
		 
		$this->addAction($category);
		 
		$text = "Default category added. Id: ".$category->getId();
		
		/*
		$repository = $this->getDoctrine()
		->getRepository("DWSBundle:Category");
		$categories = $repository->findAll();
		*/
		
		$categories = $this->searchAllAction();
		
		return $this->render("DWSBundle:Category:list.html.twig", array("text" => $text,
																			"categories" => $categories));
	}
	
	/**
 	* @Security("has_role('ROLE_ADMIN')")
 	*/
    public function createAction($name)
    {
    	$category = new Category();
    	$category->setName($name);
    	
    	$validator = $this->get("validator");
    	$errors = $validator->validate($category);
    	
    	if (count($errors) > 0) {

    		$errorsString = (string) $errors;
    	
    		return $this->render("DWSBundle::index.html.twig", array("text" => $errorsString));

    	}
    	
    	$this->addAction($category);
    	
    	return new Response("Category ".$category->getName()." added with Id: ".$category->getId());
    	
        //return $this->render("DWSBundle::index.html.twig", array("name" => $name));
    }
    
    /**
 	* @Security("has_role('ROLE_ADMIN','ROLE_USER')")
 	*/
    public function showAction($id)
    {
    	
    	/*
    	$category = $this->getDoctrine()
	    	->getRepository("DWSBundle:Category")
	    	->find($id);
    	*/
    	$category = $this->searchByIdAction($id);
    	
    	if (!$category) {
//     		throw $this->createNotFoundException(
//     				"No product found for id ".$id
//     				);
//     		return new Response("There is not any category with id: ".$id);
    
			$text = "There is not any category with id: ".$id;
			return $this->render("DWSBundle::index.html.twig", array("text" => $text));    	
    		
    	}
    
//     	return new Response("Id: ".$category->getId().
// 							"\n Name: ".$category->getName()."\n\n");
    		
    	return $this->render("DWSBundle:Category:show.html.twig", array("category" => $category));
    }
    
	/**
 	* @Security("has_role('ROLE_ADMIN','ROLE_USER')")
 	*/
	public function listAction()
	{
		/*
		$repository = $this->getDoctrine()
	        ->getRepository("DWSBundle:Category");
	    $categories = $repository->findAll();
		*/
		$categories = $this->searchAllAction();
		
		if (count($categories) === 0) {
// 			throw $this->createNotFoundException(
// 					"No products found");
// 			return new Response("There is not any category");
	
			$text = "There is not any category";
			return $this->render("DWSBundle::index.html.twig", array("text" => $text));
		}
		
// 		$list = "";
		
// 		foreach($categories as $category){
			
// 			$list .= "Id: ".$category->getId().
// 				"\n Name: ".$category->getName()."\n\n";
// 		}
		
// 		return new Response($list);
		 
		return $this->render("DWSBundle:Category:list.html.twig", array("categories" => $categories));
	}
	
	/**
 	* @Security("has_role('ROLE_ADMIN')")
 	*/
	public function deleteAction($id)
	{
		/*
		$category = $this->getDoctrine()
		->getRepository("DWSBundle:Category")
		->find($id);
		*/
		$category = $this->searchByIdAction($id);
	
		if (!$category) {
// 			throw $this->createNotFoundException(
// 					"No product found for id ".$id
// 					);
// 			return new Response("There is not any category with id: ".$id);
	
			$text = "There is not any category with id ".$id;
			return $this->render("DWSBundle::index.html.twig", array("text" => $text));
		}
		
		$this->removeAction($category);
	
		$text = "Category ".$category->getName()." with Id ". $id." removed";
		//return new Response("Category ".$category->getName()." with Id ". $id." removed");
		/*
		$repository = $this->getDoctrine()
		->getRepository("DWSBundle:Category");
		$categories = $repository->findAll();
		*/
		$categories = $this->searchAllAction();
		 
		return $this->render("DWSBundle:Category:list.html.twig", array("text" => $text,
				"categories" => $categories));
	}
	
	/**
 	* @Security("has_role('ROLE_ADMIN')")
 	*/
	public function newCategoryAction(Request $request) {
	
		$category = new Category();
	
		$form = $this->createFormBuilder($category)
			->add("name", "text", array(
					//"placeholder" 	=> "Alimentacion",
					"required"    	=> true,
					"empty_data"  	=> null))
		->add('save', 'submit', array('label' => 'Create Category'))
    	->add('saveAndAdd', 'submit', array('label' => 'Save and Add'))
    	
		->getForm();
	
		$form->handleRequest($request);
	
		if ($request->isMethod("POST") && $form->isValid()) {
			$this->addAction($category);
			
			$continueAction = $form->get('saveAndAdd')->isClicked();
				
			if($continueAction){
				$text = "Category ".$category->getName()." with Id ". $category->getId()." added";
				return $this->render("DWSBundle:Category:new.html.twig", array(
						"form" => $form->createView(),"text" => $text,
				));
			}

			//return new Response("Category ".$category->getName()." with Id ". $category->getId()." added");
			$text = "Category ".$category->getName()." with Id ". $category->getId()." added";
			return $this->render("DWSBundle::index.html.twig", array("text" => $text, "success" => true));
			
		}
	
		return $this->render("DWSBundle:Category:new.html.twig", array(
				"form" => $form->createView(),
		));
	}
	
	/**
 	* @Security("has_role('ROLE_ADMIN')")
 	*/
	public function editAction($id, Request $request) 
	{
		$category = $this->searchByIdAction($id);

		$form = $this->createFormBuilder($category)
			->add("name", "text", array(
					//"placeholder" 	=> "Alimentacion",
					"required"    	=> true,
					"empty_data"  	=> null,
					"data"			=> $category->getName()))
			->add('save', 'submit', array('label' => 'Update Category'))
			->getForm();
	
		$form->handleRequest($request);
	
		if ($request->isMethod("POST") && $form->isValid()) {
			
			$this->updateAction();
			
			//return new Response("Category ".$category->getName()." with Id ". $category->getId()." added");
			$text = "Category ".$category->getName()." with Id ". $category->getId()." updated";
			$categories = $this->searchAllAction();
			//return $this->redirectToRoute('cat_list', array("text" => $text,"categories" => $categories));
			return $this->render("DWSBundle:Category:list.html.twig", array("text" => $text,
				"categories" => $categories));
		}
	
		return $this->render("DWSBundle:Category:edit.html.twig", array(
				"form" => $form->createView(),
				"category" => $category
		));
	}
	
	private function addAction($category) {
	
		$em = $this->getDoctrine()->getManager();
		$em->persist($category);
		$em->flush();
	}
	
	private function removeAction($category) {
	
		$em = $this->getDoctrine()->getManager();
		$em->remove($category);
		$em->flush();
	}
		
	private function updateAction() {
	
		$em = $this->getDoctrine()->getManager();
		$em->flush();
	}
	
	private function searchByIdAction($id) {
	
    	return $this->getDoctrine()
	    	->getRepository("DWSBundle:Category")
	    	->find($id);
	}
	
	private function searchAllAction() {
	
		$repository = $this->getDoctrine()
			->getRepository("DWSBundle:Category");
		
		return $repository->findAll();
	}

}