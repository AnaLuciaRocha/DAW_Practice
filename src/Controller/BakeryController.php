<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use App\Controller\Bakery_modelController;

class BakeryController extends AbstractController
{
    
	private $session;
	private $bakery_model;
	private $validator;
	
	public function __construct(SessionInterface $session, Bakery_modelController $bakery_model, ValidatorInterface $validator)
    {
		$this->session = $session;
		$this->bakery_model = $bakery_model;
        $this->validator = $validator;
    }
	
		
	/**
     * @Route("/bakery", name="bakery")
     */
    public function index(): Response
    {
        return $this->render('bakery/home.html.twig', [
            'controller_name' => 'BakeryController',
        ]);
    }

    /**
     * @Route("menu/{category_id?}", name="menu")
     */
    // public function menu(Request $request, ProductsRepository $prodRepo, CategoriesRepository $categories): Response
    // {


    //     $products = $prodRepo->getProducts();
    //     $categories = $categories->getCategories();

    //     // var_dump($products[0]->getCategory()->getId());

    //     if (!$products) {
    //         $this->addFlash('danger', 'Unable to find products from database!');
    //     }
    //     if (!$categories) {
    //         $this->addFlash('danger', 'Unable to find categories from database!');
    //     }

    //     $sessionCart = $request->getSession();
    //     $cart = $sessionCart->get('cart');


    //     // $cart = $this->cart($request, $prodRepo);

    //     // var_dump(key($cart));
    //     // var_dump($cart);
    //     // die();

    //     return $this->render('eshop/home.html.twig', [
    //         'controller_name' => 'EshopController',
    //         'products' => $products,
    //         'categories' => $categories,
    //         'cart' => $cart,
    //         'product' => $prodRepo

    //         // 'cart_size' => $cart['size'],
    //         // 'cart_price' => $cart['price']
    //     ]);
    // }

}
