<?php

namespace DWSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use DWSBundle\Entity\Product;
use DWSBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends Controller {

    public static $id_default = 1;
    public static $name_default = "Pan de Pueblo";
    public static $price_default = 00.85;
    public static $description_default = "Barra de pan de pueblo cocida a leña de 250 gr";

    private function initCategory() {
        $category = $this->searchByNameAction(CategoryController::$name_default);

        if (!$category) {
            $category = new Category();
            $category->setName(CategoryController::$name_default);

            $validator = $this->get("validator");
            $errors = $validator->validate($category);

            if (count($errors) > 0) {

                $errorsString = (string) $errors;

                return $this->render("DWSBundle::index.html.twig", array("text" => $errorsString));
            }

            $category->addAction($category);
        }

        return $category;
    }

    /**
     * @Security("has_role('ROLE_APP_ADMIN')")
     */
    public function createStaticAction() {
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

        $products = $this->searchAllAction();

        return $this->render("DWSBundle:Product:list.html.twig", array("product" => $product,
                    "flashproductadd" => true, "products" => $products));
    }

    /**
     * @Security("has_role('ROLE_APP_ADMIN')")
     */
    public function createParamAction($name, $price) {
        $category = $this->initCategory();

        $product = new Product();
        $product->setName($name);
        $product->setPrice($price);
        $product->setDescription("NULL - " . $name);
        $product->setCategory($category);

        $validator = $this->get("validator");
        $errors = $validator->validate($product);

        if (count($errors) > 0) {

            $errorsString = (string) $errors;

            return $this->render("DWSBundle::index.html.twig", array("text" => $errorsString));
        }


        $this->addAction($product);

        return new Response("Created product:
								\n Id: " . $product->getId() .
                "\n Category: " . $product->getCategory()->getName() .
                "\n	Name: " . $product->getName() .
                "\n	Price: " . $product->getPrice() .
                "\n Description: " . $product->getDescription() . "\n\n");
    }

    /**
     * @Security("has_role('ROLE_APP_ADMIN'||'ROLE_USER')")
     */
    public function showAction($id) {
        $product = $this->searchByIdAction($id);

        if (!$product) {

            return $this->render("DWSBundle::index.html.twig", array("flashnoproductid" => true,
                        "id" => $id));
        }

        return $this->render("DWSBundle:Product:show.html.twig", array("product" => $product));
    }

    /**
     * @Security("has_role('ROLE_APP_ADMIN'||'ROLE_USER')")
     */
    public function listAction() {
        $products = $this->searchAllAction();

        if (count($products) === 0) {

            return $this->render("DWSBundle::index.html.twig", array("flashnoproducts" => true));
        }

        return $this->render("DWSBundle:Product:list.html.twig", array("products" => $products
        ));
    }

    /**
     * @Security("has_role('ROLE_APP_ADMIN'||'ROLE_USER')")
     */
    public function listByCategoryAction($category) {
        $cat = $this->searchByNameAction($category);

        if (count($cat) === 0) {

            return $this->render("DWSBundle::index.html.twig", array("flashnocategoryname" => true,
                        "name" => $name));
        }

        $products = $cat->getProducts();

        return $this->render("DWSBundle:Product:listByCat.html.twig", array("category" => $cat
        ));
    }

    /**
     * @Security("has_role('ROLE_APP_ADMIN'||'ROLE_USER')")
     */
    public function listAllByCategoryAction() {
        $repository = $this->getDoctrine()
                ->getRepository("DWSBundle:Category");

        $categories = $repository->findAll();

        if (count($categories) === 0) {

            return $this->render("DWSBundle::index.html.twig", array("flashnocategories" => true));
        }

        return $this->render("DWSBundle:Product:listAllByCat.html.twig", array("categories" => $categories
        ));
    }

    /**
     * @Security("has_role('ROLE_APP_ADMIN')")
     */
    public function deleteAction($id) {
        $product = $this->searchByIdAction($id);

        if (!$product) {
            return $this->render("DWSBundle::index.html.twig", array("flashnoproductid" => true,
                        "id" => $id));
        }

        $this->removeAction($product);
        $products = $this->searchAllAction();

        return $this->render("DWSBundle:Product:list.html.twig", array("product" => $product,
                    "flashproductremove" => true, "products" => $products
        ));
    }

    /**
     * @Security("has_role('ROLE_APP_ADMIN')")
     */
    public function newProductAction(Request $request) {

        $product = new Product();

        $form = $this->createFormBuilder($product, ['translation_domain' => 'DWSBundle'])
                ->add("name", "text", array(
                    //"placeholder" 	=> "Pan Bimbo Familiar",
                    'label' => 'product.name',
                    "required" => true,
                    "empty_data" => null))
                ->add("category", "entity", array(
                    "class" => "DWSBundle:Category",
                    'label' => 'product.category',
                    "choice_label" => "name",
                    //"placeholder" 	=> "Alimentacion",
                    "required" => true,
                    "empty_data" => null))
                ->add("price", "money", array(
                    "currency" => "EUR",
                    'label' => 'product.price',
                    //"placeholder" 	=> 01.99,
                    "scale" => 2,
                    "required" => true,
                    "empty_data" => null))
                ->add("description", "textarea", array(
                    'label' => 'product.description',
                    //"placeholder" 	=> "Brief description",
                    "required" => false))
                ->add('save', 'submit', array('label' => 'action.save'))
                ->add('saveAndAdd', 'submit', array('label' => 'action.saveAndAdd'))
                ->getForm();

        $form->handleRequest($request);

        if ($request->isMethod("POST") && $form->isValid()) {
            $this->addAction($product);

            $continueAction = $form->get('saveAndAdd')->isClicked();

            if ($continueAction) {

                return $this->render("DWSBundle:Product:new.html.twig", array(
                            "form" => $form->createView(), "flashproductadd" => true, "product" => $product,
                ));
            }

            $products = $this->searchAllAction();

            return $this->render("DWSBundle:Product:list.html.twig", array("product" => $product,
                        "flashproductadd" => true, "products" => $products));
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

        $form = $this->createFormBuilder($product, ['translation_domain' => 'DWSBundle'])
                ->add("name", "text", array(
                    //"placeholder" 	=> "Pan Bimbo Familiar",
                    'label' => 'product.name',
                    "required" => true,
                    "empty_data" => null,
                    "data" => $product->getName()))
                ->add("category", "entity", array(
                    'label' => 'product.category',
                    "class" => "DWSBundle:Category",
                    "choice_label" => "name",
                    //"placeholder" 	=> "Alimentacion",
                    "required" => true,
                    "empty_data" => null,
                    "data" => $product->getCategory()))
                ->add("price", "money", array(
                    'label' => 'product.price',
                    "currency" => "EUR",
                    //"placeholder" 	=> 01.99,
                    "scale" => 2,
                    "required" => true,
                    "empty_data" => null,
                    "data" => $product->getPrice()))
                ->add("description", "textarea", array(
                    'label' => 'product.description',
                    //"placeholder" 	=> "Brief description",
                    "required" => false,
                    "data" => $product->getDescription()))
                ->add('save', 'submit', array('label' => 'action.update'))
                ->getForm();

        $form->handleRequest($request);

        if ($request->isMethod("POST") && $form->isValid()) {

            $this->updateAction();

            $products = $this->searchAllAction();
            return $this->render("DWSBundle:Product:list.html.twig", array("product" => $product,
                        "flashproductupdate" => true, "products" => $products));
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

    private function searchByNameAction($name) {

        $repository = $this->getDoctrine()
                ->getRepository("DWSBundle:Category");

        return $repository->findOneByName($name);
    }

}
