<?php

namespace DWSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use DWSBundle\Entity\Person;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use \DateTime;

class PersonController extends Controller
{
	public static $id_default = 1;
	public static $name_default = "Joan Dídac Viana Fons";
	public static $age_default = 27;
	public static $birthDate_default = "1988-07-30";
	public static $height_default = 175;
	public static $email_default = "joanviana.wembail@gmail.com";
	public static $phone_default = 663328199;
	public static $gender_default = "m";
	public static $descends_default = 2;
	public static $vehicle_default = false;
	public static $preferredLanguage_default = "C++";
	public static $englishLevel_default = 4;
	public static $personalWebSite_default = "http://www.joanviana.hol.es";
	public static $cardNumber_default = "5555555555554444";
	public static $iban_default = "ES9121000418450200051332";
	/*
	private function initPerson(){
		
		$repository = $this->getDoctrine()
		->getRepository("DWSBundle:Person");
		
		$person= $repository->findOneByName(self::$name_default);
		
		if(!$person){
			$person = new Person();
			$person->setName(self::$name_default);
			$person->setAge(self::$age_default);
			$date = new DateTime;
			$birth = $date::createFromFormat("yyyy-MM-dd", self::$birthDate_default);
			$person->setBirthDate($birth);
			$person->setHeight(self::$height_default);
			$person->setEmail(self::$email_default);
			$person->setPhone(self::$phone_default);
			$person->setGender(self::$gender_default);
			$person->setDescends(self::$descends_default);
			$person->setVehicle(self::$vehicle_default);
			$person->setPreferredLanguage(self::$preferredLanguage_default);
			$person->setEnglishLevel(self::$englishLevel_default);
			$person->setPersonalWebSite(self::$personalWebSite_default);
			$person->setCardNumber(self::$cardNumber_default);
			$person->setIBAN(self::$iban_default);
			
			$validator = $this->get("validator");
			$errors = $validator->validate($person);
			
			if (count($errors) > 0) {

				$errorsString = (string) $errors;
			
				return $this->render("DWSBundle::index.html.twig", array("text" => $errorsString));

			}
			
			 
			$this->addAction($person);
		}
		
		return $person;
	}
	*/
	
	public function createStaticAction()
	{
		$person = new Person();
		$person->setName(self::$name_default);
		$person->setAge(self::$age_default);
		$date = new DateTime(self::$birthDate_default);
		$person->setBirthDate($date);
		$person->setHeight(self::$height_default);
		$person->setEmail(self::$email_default);
		$person->setPhone(self::$phone_default);
		$person->setGender(self::$gender_default);
		$person->setDescends(self::$descends_default);
		$person->setVehicle(self::$vehicle_default);
		$person->setPreferredLanguage(self::$preferredLanguage_default);
		$person->setEnglishLevel(self::$englishLevel_default);
		$person->setPersonalWebSite(self::$personalWebSite_default);
		$person->setCardNumber(self::$cardNumber_default);
		$person->setIBAN(self::$iban_default);
		
		$validator = $this->get("validator");
		$errors = $validator->validate($person);
		
		if (count($errors) > 0) {

			$errorsString = (string) $errors;
		
			return $this->render("DWSBundle::index.html.twig", array("text" => $errorsString));

		}
		
		 
		$this->addAction($person);
		 
		$text = "Default person added. Id: ".$person->getId();
		//return new Response("Default person added. Id: ".$person->getId());
		
		$repository = $this->getDoctrine()
		->getRepository("DWSBundle:Person");
		
		$persons = $repository->findAll();
		 
		return $this->render("DWSBundle:Person:list.html.twig", array("text" => $text,
																			"persons" => $persons));
	}
	
	public function listAction()
	{
		$repository = $this->getDoctrine()
	        ->getRepository("DWSBundle:Person");
		
	    $persons = $repository->findAll();
	
		if (count($persons) === 0) {
			/*
			throw $this->createNotFoundException(
					"No persons found");
			return new Response("There is not any person");
			*/
			$text = "There is not any person";
			return $this->render("DWSBundle::index.html.twig", array("text" => $text));
		}
		
		/*
		
		$list = "";		
		foreach($persons as $person){
			
			$list .= "Id: ".$person->getId().
				"\n Name: ".$person->getName().
				"\n Age: ".$person->getAge().
				"\n BirthDate: ".$person->getBirthDate()->format("yyyy-MM-dd").
				"\n Height: ".$person->getHeight().
				"\n Email: ".$person->getEmail().
				"\n Phone: ".$person->getPhone().
				"\n Gender: ".$person->getGender().
				"\n Descends: ".$person->getDescends().
				"\n Vehicle: ".$person->getVehicle().
				"\n PeferredLanguage: ".$person->getPreferredLanguage().
				"\n EnglishLevel: ".$person->getEnglishLevel().
				"\n PersonalWebSite: ".$person->getPersonalWebSite().
				"\n CardNumber: ".$person->getCardNumber().
				"\n IBAN: ".$person->getIBAN()."\n\n";
		}
		
		return new Response($list);
		*/
		 
		return $this->render("DWSBundle:Person:list.html.twig", array("persons" => $persons
		));
	}
	

	public function deleteAction($id)
	{
		$person = $this->getDoctrine()
		->getRepository("DWSBundle:Person")									
		->find($id);
	
		if (!$person) {
			/*
			throw $this->createNotFoundException(
					"No person found for id ".$id
					);
			return new Response("There is not any person with id: ".$id);
			*/
			$text = "There is not any person with id: ".$id;
			return $this->render("DWSBundle::index.html.twig", array("text" => $text));
		}
		
		$this->removeAction($person);
		
		$text = "Person ".$person->getName()." with Id ". $id." removed";
		//return new Response("Person ".$person->getName()." with Id ". $id." removed");
		
		$repository = $this->getDoctrine()
			->getRepository("DWSBundle:Person");
		
		$persons = $repository->findAll();
		 
		return $this->render("DWSBundle:Person:list.html.twig", array("text" => $text,
																			"persons" => $persons
		));
	}
	
	public function showAction($id)
	{
		$person = $this->getDoctrine()
		->getRepository("DWSBundle:Person")
		->find($id);
	
		if (!$person) {
			/*
			throw $this->createNotFoundException(
					"No person found for id ".$id
					);
			return new Response("There is not any person with id: ".$id);
			*/
			$text = "There is not any person with id: ".$id;
			return $this->render("DWSBundle::index.html.twig", array("text" => $text));
		}
	
		/*		
		return new Response(
			"Id: ".$person->getId().
			"\n Name: ".$person->getName().
			"\n Age: ".$person->getAge().
			"\n BirthDate: ".$person->getBirthDate()->format("yyyy-MM-dd").
			"\n Height: ".$person->getHeight().
			"\n Email: ".$person->getEmail().
			"\n Phone: ".$person->getPhone().
			"\n Gender: ".$person->getGender().
			"\n Descends: ".$person->getDescends().
			"\n Vehicle: ".$person->getVehicle().
			"\n PeferredLanguage: ".$person->getPreferredLanguage().
			"\n EnglishLevel: ".$person->getEnglishLevel().
			"\n PersonalWebSite: ".$person->getPersonalWebSite().
			"\n CardNumber: ".$person->getCardNumber().
			"\n IBAN: ".$person->getIBAN()."\n\n");
		*/
		 
		return $this->render("DWSBundle:Person:show.html.twig", array("person" => $person));
	}
	
	public function newAction(Request $request) {
	
		$person = new Person();
	
		$form = $this->createFormBuilder($person)
			->add("name", "text", array(
					//"placeholder" 	=> "John Smith",
					"required"    	=> true,
					"empty_data"  	=> null))
			->add("age", "integer", array(
					//"placeholder" 	=> 27,
					//"data"			=> 18,
					"required"    	=> false))
			->add("birthDate", "date", array(
					//"placeholder" 	=> array("year" => "1988", "month" => "05", "day" => "27"),
					"format" 		=> "yyyy-MM-dd",
					"required"    	=> true,
					"empty_data"  	=> null))
			->add("height", "integer", array(
					//"placeholder" 	=> 178,
					"required"    	=> false))
			->add("email", "email", array(
					//"placeholder" 	=> "example@domain.com",
					"required"    	=> true,
					"empty_data"  	=> null))
			->add("phone", "number", array(
					//"placeholder" 	=> 654123123,
					"required"    	=> true,
					"empty_data"  	=> null))
			->add("gender", "choice", array(
					//"placeholder" 	=> "Femenino",
					"required"    	=> true,
    				"choices" => array("Femenino" => "f", "Masculino" => "m"),
    				"choices_as_values" => true,
					"empty_data"  	=> null))
			->add("descends", "number", array(
					//"placeholder" 	=> 2,
					"scale"			=> 0,
					"required"    	=> false))
			->add("vehicle", "checkbox", array(
					"label"    => "¿Vehículo propio?",
					"required"    	=> false))
			->add("preferredLanguage","choice", array(
					//"placeholder" 	=> "Programming Language",
					"required"    	=> false,
					"multiple" 		=> false,
					"expanded" 		=> false,
    				"choices" 		=> array("Java", "C", "C++", "Python", "C#", "PHP")))
			->add("englishLevel", "choice", array(
					//"required"    	=> false,
					"data"			=> 1,
					"multiple" 		=> false,
					"expanded" 		=> true,
    				"choices" 		=> array("A1" => 1, "A2" => 2, "B1" => 3, "B2" => 4, "C1" => 5, "C2" => 6),
    				"choices_as_values" => true))
			->add("personalWebSite", "url", array(
					//"placeholder" 	=> "http://www.example.com",
					"required"    	=> false))
			->add("cardNumber", "text", array(
					//"placeholder" 	=> "5555555555554444",
					"required"    	=> false))
			->add("IBAN", "text", array(
					//"placeholder" 	=> "ES9121000418450200051332",
					"required"    	=> false))
			->add('save', 'submit', array('label' => 'Create Person'))
    		->add('saveAndAdd', 'submit', array('label' => 'Save and Add'))

			->getForm();
	
		$form->handleRequest($request);
	
		if ($request->isMethod("POST") && $form->isValid()) {
			
			$this->addAction($person);
			
			$continueAction = $form->get('saveAndAdd')->isClicked();
			
			if($continueAction){
				$text = "Person ".$person->getName()." with Id ". $person->getId()." added";
				return $this->render("DWSBundle:Person:new.html.twig", array(
						"form" => $form->createView(),"text" => $text,
				));
			}

			//return new Response("Person ".$person->getName()." with Id ". $person->getId()." added");
			$text = "Person ".$person->getName()." with Id ". $person->getId()." added";
			return $this->render("DWSBundle::index.html.twig", array("text" => $text, "success" => true));
	
		}
	
		return $this->render("DWSBundle:Person:new.html.twig", array(
				"form" => $form->createView()
		));
	}
	
	private function addAction($person) {
	
		$em = $this->getDoctrine()->getManager();
		$em->persist($person);
		$em->flush();
	}
	
	private function removeAction($person) {
	
		$em = $this->getDoctrine()->getManager();
		$em->remove($person);
		$em->flush();
	}
}