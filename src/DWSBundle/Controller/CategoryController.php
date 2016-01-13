<?php

namespace DWSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use DWSBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller {

    public static $id_default = 1;
    public static $name_default = "Alimentacion";

    /**
     * @Security("has_role('ROLE_APP_ADMIN')")
     */
    public function createStaticAction() {
        $category = new Category();
        $category->setName(self::$name_default);

        $validator = $this->get("validator");
        $errors = $validator->validate($category);

        if (count($errors) > 0) {

            $errorsString = (string) $errors;

            return $this->render("DWSBundle::index.html.twig", array("text" => $errorsString));
        }


        $this->addAction($category);


        $categories = $this->searchAllAction();

        return $this->render("DWSBundle:Category:list.html.twig", array("category" => $category,
                    "categories" => $categories));
    }

    /**
     * @Security("has_role('ROLE_APP_ADMIN')")
     */
    public function createAction($name) {
        $category = new Category();
        $category->setName($name);

        $validator = $this->get("validator");
        $errors = $validator->validate($category);

        if (count($errors) > 0) {

            $errorsString = (string) $errors;

            return $this->render("DWSBundle::index.html.twig", array("text" => $errorsString));
        }

        $this->addAction($category);

        $categories = $this->searchAllAction();

        return $this->render("DWSBundle:Category:list.html.twig", array("category" => $category, "flashcategoryadd" => true,
                    "categories" => $categories,
        ));
    }

    /**
     * @Security("has_role('ROLE_APP_ADMIN'||'ROLE_USER')")
     */
    public function showAction($id) {

        $category = $this->searchByIdAction($id);

        if (!$category) {

            $categories = $this->searchAllAction();

            return $this->render("DWSBundle:Category:list.html.twig", array("flashcategoryremove" => true,
                        "category" => $category, "categories" => $categories));
        }

        return $this->render("DWSBundle:Category:show.html.twig", array("category" => $category));
    }

    /**
     * @Security("has_role('ROLE_APP_ADMIN'||'ROLE_USER')")
     */
    public function listAction() {

        $categories = $this->searchAllAction();

        if (count($categories) === 0) {

            return $this->render("DWSBundle::index.html.twig", array("flashnocategories" => true));
        }

        return $this->render("DWSBundle:Category:list.html.twig", array("categories" => $categories));
    }

    /**
     * @Security("has_role('ROLE_APP_ADMIN')")
     */
    public function deleteAction($id) {

        $category = $this->searchByIdAction($id);

        if (!$category) {

            return $this->render("DWSBundle::index.html.twig", array("flashnocategoryid" => true,
                        "id" => $id));
        }

        $this->removeAction($category);

        $categories = $this->searchAllAction();

        return $this->render("DWSBundle:Category:list.html.twig", array("category" => $category,
                    "flashcategoryremove" => true, "categories" => $categories));
    }

    /**
     * @Security("has_role('ROLE_APP_ADMIN')")
     */
    public function newCategoryAction(Request $request) {

        $category = new Category();

        $form = $this->createFormBuilder($category, ['translation_domain' => 'DWSBundle'])
                ->add("name", "text", array(
                    //"placeholder" 	=> "Alimentacion",
                    "required" => true,
                    "empty_data" => null))
                ->add('save', 'submit', array('label' => 'action.save'))
                ->add('saveAndAdd', 'submit', array('label' => 'action.saveAndAdd'))
                ->getForm();

        $form->handleRequest($request);

        if ($request->isMethod("POST") && $form->isValid()) {
            $this->addAction($category);

            $continueAction = $form->get('saveAndAdd')->isClicked();

            if ($continueAction) {

                return $this->render("DWSBundle:Category:new.html.twig", array(
                            "form" => $form->createView(), "category" => $category, "flashcategoryadd" => true,
                ));
            }

            $categories = $this->searchAllAction();

            return $this->render("DWSBundle:Category:list.html.twig", array("category" => $category, "flashcategoryadd" => true,
                        "categories" => $categories,
            ));
        }

        return $this->render("DWSBundle:Category:new.html.twig", array(
                    "form" => $form->createView(),
        ));
    }

    /**
     * @Security("has_role('ROLE_APP_ADMIN')")
     */
    public function editAction($id, Request $request) {
        $category = $this->searchByIdAction($id);

        $form = $this->createFormBuilder($category, ['translation_domain' => 'DWSBundle'])
                ->add("name", "text", array(
                    "label" => "category.name",
                    //"placeholder" 	=> "Alimentacion",
                    "required" => true,
                    "empty_data" => null,
                    "data" => $category->getName()))
                ->add('save', 'submit', array('label' => 'action.update'))
                ->getForm();

        $form->handleRequest($request);

        if ($request->isMethod("POST") && $form->isValid()) {

            $this->updateAction();
            $categories = $this->searchAllAction();
            return $this->render("DWSBundle:Category:list.html.twig", array("category" => $category, "flashcategoryupdate" => true,
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
