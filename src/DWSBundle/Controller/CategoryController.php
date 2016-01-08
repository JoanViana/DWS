<?php

namespace DWSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
		
		$repository = $this->getDoctrine()
		->getRepository("DWSBundle:Category");
		
		$categories = $repository->findAll();
		 
		return $this->render("DWSBundle:Category:list.html.twig", array("text" => $text,
																			"categories" => $categories));
	}
	
    public function createAction($name)
    {
    	$category = new Category();
    	$category->setName($name);
    	
    	$validator = $this->get("validator");
    	$errors = $validator->validate($category);
    	
    	if (count($errors) > 0) {
    		/*
    		 * Uses a __toString method on the $errors variable which is a
    		 * ConstraintViolationList object. This gives us a nice string
    		 * for debugging.
    		 */
    		$errorsString = (string) $errors;
    	
    		return $this->render("DWSBundle::index.html.twig", array("text" => $errorsString));

    	}
    	 
    	
    	$this->addAction($category);
    	
    	return new Response("Category ".$category->getName()." added with Id: ".$category->getId());
    	
        //return $this->render("DWSBundle::index.html.twig", array("name" => $name));
    }
    
    public function showAction($id)
    {
    	$category = $this->getDoctrine()
	    	->getRepository("DWSBundle:Category")
	    	->find($id);
    
    	if (!$category) {
//     		throw $this->createNotFoundException(
//     				"No product found for id ".$id
//     				);
//     		return new Response("There is not any category with id: ".$id);
    
			$text = "There is not any category with id: ".$id;
			return $this->render("DWSBundle::index.html.twig", array("text" => $text));    	}
    
//     	return new Response("Id: ".$category->getId().
// 							"\n Name: ".$category->getName()."\n\n");
    		
    	return $this->render("DWSBundle:Category:show.html.twig", array("category" => $category));
    }
    

	public function listAction()
	{
		$repository = $this->getDoctrine()
	        ->getRepository("DWSBundle:Category");
		
	    $categories = $repository->findAll();
	
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
	

	public function deleteAction($id)
	{
		$category = $this->getDoctrine()
		->getRepository("DWSBundle:Category")
		->find($id);
	
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
		
		$repository = $this->getDoctrine()
		->getRepository("DWSBundle:Category");
		
		$categories = $repository->findAll();
		 
		return $this->render("DWSBundle:Category:list.html.twig", array("text" => $text,
				"categories" => $categories));
	}
	
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
	
	public function getAllAction() {
	
	    $repository = $this->getDoctrine()
	       ->getRepository("DWSBundle:Category");
	    return $repository->findAll();
	}
}