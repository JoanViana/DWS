<?php

namespace DWSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use DWSBundle\Entity\Person;
use Symfony\Component\HttpFoundation\Request;
use \DateTime;

class PersonController extends Controller {

    public static $id_default = 1;
    public static $name_default = "Joan Dídac Viana Fons";
    public static $age_default = 27;
    public static $birthDate_default = "1988-07-30";
    public static $height_default = 175;
    public static $email_default = "joanviana.wembail@gmail.com";
    public static $phone_default = 666999666;
    public static $gender_default = "m";
    public static $descends_default = 2;
    public static $vehicle_default = false;
    public static $preferredLanguage_default = "C++";
    public static $englishLevel_default = 4;
    public static $personalWebSite_default = "http://www.joanviana.hol.es";
    public static $cardNumber_default = "5555555555554444";
    public static $iban_default = "ES9121000418450200051332";
    
    /**
     * @Security("has_role('ROLE_APP_ADMIN')")
     */
    public function createStaticAction() {
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

        $persons = $this->searchAllAction();
        return $this->render("DWSBundle:Person:list.html.twig", array("person" => $person,
                    "flashpersonadd" => true,
                    "persons" => $persons));
    }
    
    /**
     * @Security("has_role('ROLE_APP_ADMIN'||'ROLE_USER')")
     */
    public function listAction() {
        $persons = $this->searchAllAction();

        if (count($persons) === 0) {

            return $this->render("DWSBundle::index.html.twig", array("flashnopersons" => true));
        }

        return $this->render("DWSBundle:Person:list.html.twig", array("persons" => $persons
        ));
    }
    
    /**
     * @Security("has_role('ROLE_APP_ADMIN')")
     */
    public function deleteAction($id) {
        $person = $this->searchByIdAction($id);

        if (!$person) {

            return $this->render("DWSBundle::index.html.twig", array("flashnopersonid" => true,
                        "id" => $id));
        }

        $this->removeAction($person);

        $persons = $this->searchAllAction();
        return $this->render("DWSBundle:Person:list.html.twig", array("person" => $person,
                    "flashpersonremove" => true,
                    "persons" => $persons));
    }

    /**
     * @Security("has_role('ROLE_APP_ADMIN'||'ROLE_USER')")
     */
    public function showAction($id) {
        $person = $this->searchByIdAction($id);
        if (!$person) {

            return $this->render("DWSBundle::index.html.twig", array("flashnopersonid" => true,
                        "id" => $id));
        }


        return $this->render("DWSBundle:Person:show.html.twig", array("person" => $person));
    }

    /**
     * @Security("has_role('ROLE_APP_ADMIN')")
     */
    public function newAction(Request $request) {

        $person = new Person();

        $form = $this->createFormBuilder($person, ['translation_domain' => 'DWSBundle'])
                ->add("name", "text", array(
                    //"placeholder" 	=> "John Smith",
                    'label' => 'person.name',
                    "required" => true,
                    "empty_data" => null))
                ->add("age", "integer", array(
                    //"placeholder" 	=> 27,
                    //"data"			=> 18,
                    'label' => 'person.age',
                    "required" => false))
                ->add("birthDate", "date", array(
                    //"placeholder" 	=> array("year" => "1988", "month" => "05", "day" => "27"),
                    "format" => "yyyy-MM-dd",
                    'label' => 'person.birthDate',
                    "required" => true,
                    "empty_data" => null))
                ->add("height", "integer", array(
                    //"placeholder" 	=> 178,
                    'label' => 'person.height',
                    "required" => false))
                ->add("email", "email", array(
                    //"placeholder" 	=> "example@domain.com",
                    'label' => 'person.email',
                    "required" => true,
                    "empty_data" => null))
                ->add("phone", "number", array(
                    //"placeholder" 	=> 654123123,
                    'label' => 'person.phone',
                    "required" => true,
                    "empty_data" => null))
                ->add("gender", "choice", array(
                    //"placeholder" 	=> "Femenino",
                    'label' => 'person.gender',
                    "required" => true,
                    "choices" => array("form.ch.female" => "f", "form.ch.male" => "m"),
                    "choices_as_values" => true,
                    "empty_data" => null))
                ->add("descends", "number", array(
                    'label' => 'person.descends',
                    //"placeholder" 	=> 2,
                    "scale" => 0,
                    "required" => false))
                ->add("vehicle", "checkbox", array(
                    'label' => 'person.vehicle',
                    "required" => false))
                ->add("preferredLanguage", "choice", array(
                    //"placeholder" 	=> "Programming Language",
                    "required" => false,
                    'label' => 'person.preferredLanguage',
                    "multiple" => false,
                    "expanded" => false,
                    "choices" => array("Java", "C", "C++", "Python", "C#", "PHP")))
                ->add("englishLevel", "choice", array(
                    'label' => 'person.englishLevel',
                    //"required"    	=> false,
                    "data" => 1,
                    "multiple" => false,
                    "expanded" => true,
                    "choices" => array("A1" => 1, "A2" => 2, "B1" => 3, "B2" => 4, "C1" => 5, "C2" => 6),
                    "choices_as_values" => true))
                ->add("personalWebSite", "url", array(
                    'label' => 'person.personalWebSite',
                    //"placeholder" 	=> "http://www.example.com",
                    "required" => false))
                ->add("cardNumber", "text", array(
                    'label' => 'person.cardNumber',
                    //"placeholder" 	=> "5555555555554444",
                    "required" => false))
                ->add("IBAN", "text", array(
                    'label' => 'person.iban',
                    //"placeholder" 	=> "ES9121000418450200051332",
                    "required" => false))
                ->add('save', 'submit', array('label' => 'action.save'))
                ->add('saveAndAdd', 'submit', array('label' => 'action.saveAndAdd'))
                ->getForm();

        $form->handleRequest($request);

        if ($request->isMethod("POST") && $form->isValid()) {

            $this->addAction($person);

            $continueAction = $form->get('saveAndAdd')->isClicked();

            if ($continueAction) {

                return $this->render("DWSBundle:Person:new.html.twig", array(
                            "form" => $form->createView(), "person" => $person, "flashpersonadd" => true,
                ));
            }

            $persons = $this->searchAllAction();

            return $this->render("DWSBundle:Person:list.html.twig", array("person" => $person, "flashpersonadd" => true,
                        "persons" => $persons,
            ));
        }

        return $this->render("DWSBundle:Person:new.html.twig", array(
                    "form" => $form->createView()
        ));
    }

    /**
     * @Security("has_role('ROLE_APP_ADMIN')")
     */
    public function editAction($id,Request $request) {

        $person = $this->searchByIdAction($id);

        $form = $this->createFormBuilder($person, ['translation_domain' => 'DWSBundle'])
                ->add("name", "text", array(
                    //"placeholder" 	=> "John Smith",
                    'label' => 'person.name',
                    "required" => true,
                    "empty_data" => null,
                    "data" => $person->getName()))
                ->add("age", "integer", array(
                    //"placeholder" 	=> 27,
                    //"data"			=> 18,
                    'label' => 'person.age',
                    "required" => false,
                    "data" => $person->getAge()))
                ->add("birthDate", "date", array(
                    //"placeholder" 	=> array("year" => "1988", "month" => "05", "day" => "27"),
                    "format" => "yyyy-MM-dd",
                    'label' => 'person.birthDate',
                    "required" => true,
                    "empty_data" => null,
                    "data" => $person->getBirthDate()))
                ->add("height", "integer", array(
                    //"placeholder" 	=> 178,
                    'label' => 'person.height',
                    "required" => false,
                    "data" => $person->getHeight()))
                ->add("email", "email", array(
                    //"placeholder" 	=> "example@domain.com",
                    'label' => 'person.email',
                    "required" => true,
                    "empty_data" => null,
                    "data" => $person->getEmail()))
                ->add("phone", "number", array(
                    //"placeholder" 	=> 654123123,
                    'label' => 'person.phone',
                    "required" => true,
                    "empty_data" => null,
                    "data" => $person->getPhone()))
                ->add("gender", "choice", array(
                    //"placeholder" 	=> "Femenino",
                    'label' => 'person.gender',
                    "required" => true,
                    "choices" => array("form.ch.female" => "f", "form.ch.male" => "m"),
                    "choices_as_values" => true,
                    "empty_data" => null,
                    "data" => $person->getGender()))
                ->add("descends", "number", array(
                    'label' => 'person.descends',
                    //"placeholder" 	=> 2,
                    "scale" => 0,
                    "required" => false,
                    "data" => $person->getDescends()))
                ->add("vehicle", "checkbox", array(
                    'label' => 'person.vehicle',
                    "required" => false,
                    "data" => $person->getVehicle()))
                ->add("preferredLanguage", "choice", array(
                    //"placeholder" 	=> "Programming Language",
                    "required" => false,
                    'label' => 'person.preferredLanguage',
                    "multiple" => false,
                    "expanded" => false,
                    "choices" => array("Java", "C", "C++", "Python", "C#", "PHP"),
                    "data" => $person->getPreferredLanguage()))
                ->add("englishLevel", "choice", array(
                    'label' => 'person.englishLevel',
                    //"required"    	=> false,
                    "data" => 1,
                    "multiple" => false,
                    "expanded" => true,
                    "choices" => array("A1" => 1, "A2" => 2, "B1" => 3, "B2" => 4, "C1" => 5, "C2" => 6),
                    "choices_as_values" => true,
                    "data" => $person->getEnglishLevel()))
                ->add("personalWebSite", "url", array(
                    'label' => 'person.personalWebSite',
                    //"placeholder" 	=> "http://www.example.com",
                    "required" => false,
                    "data" => $person->getPersonalWebSite()))
                ->add("cardNumber", "text", array(
                    'label' => 'person.cardNumber',
                    //"placeholder" 	=> "5555555555554444",
                    "required" => false,
                    "data" => $person->getCardNumber()))
                ->add("IBAN", "text", array(
                    'label' => 'person.iban',
                    //"placeholder" 	=> "ES9121000418450200051332",
                    "required" => false,
                    "data" => $person->getIBAN()))
                ->add('save', 'submit', array('label' => 'action.upload'))
                ->getForm();

        $form->handleRequest($request);

        if ($request->isMethod("POST") && $form->isValid()) {

            $this->updateAction($person);

            $persons = $this->searchAllAction();

            return $this->render("DWSBundle:Person:list.html.twig", array("person" => $person, "flashpersonupdate" => true,
                        "persons" => $persons,
            ));
        }

        return $this->render("DWSBundle:Person:edit.html.twig", array(
                    "form" => $form->createView(), "person" => $person,
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

    private function searchByIdAction($id) {

        return $this->getDoctrine()
                        ->getRepository("DWSBundle:Person")
                        ->find($id);
    }

    private function searchAllAction() {

        $repository = $this->getDoctrine()
                ->getRepository("DWSBundle:Person");

        return $repository->findAll();
    }
    
    private function updateAction() {

        $em = $this->getDoctrine()->getManager();
        $em->flush();
    }

}
