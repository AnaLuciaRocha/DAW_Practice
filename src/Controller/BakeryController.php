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

use App\Repository\CategoriesRepository;
use App\Repository\ProductsRepository;
use App\Bundle\Controller;
use App\Entity\OrderItems;
use App\Entity\Orders;
use App\Entity\Products;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Repository\OrdersRepository;
use Symfony\Component\HttpFoundation\JsonResponse;



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
    public function menu(Request $request, ProductsRepository $prodRepo, CategoriesRepository $categories): Response
    {


        $products = $prodRepo->getProductsWithLimit(0,2);
        $categories = $categories->getCategories();

        // var_dump($products[0]->getCategory()->getId());

        if (!$products) {
            $this->addFlash('danger', 'Unable to find products from database!');
        }
        if (!$categories) {
            $this->addFlash('danger', 'Unable to find categories from database!');
        }

        $sessionCart = $request->getSession();
        $cart = $sessionCart->get('cart');

        
    
        // $this->ajaxGetProductsAction($request);

        return $this->render('bakery/menu.html.twig', [
            'products' => $products,
            'categories' => $categories,
            'cart' => $cart,
            'product' => $prodRepo

            // 'cart_size' => $cart['size'],
            // 'cart_price' => $cart['price']
        ]);
    }


    /**
     * @Route("ajax/{index}", name="ajax")
     */
    public function ajaxGetProductsAction(Request $request, int $index, ProductsRepository $prodRepo, CategoriesRepository $categories): Response
{
    $entityManager = $this->getDoctrine()->getManager();

    
    // // dump($request->get('index'));

    // if ($request->isXMLHttpRequest()) {         
    //     return new JsonResponse(array('data' => 'this is a json response'));
    // }

    // return new Response('This is not ajax!', 400);
    $products = $prodRepo->getProductsWithLimit($index,2);
    $categories = $categories->getCategories();

        // var_dump($products[0]->getCategory()->getId());

        if (!$products) {
            $this->addFlash('danger', 'Unable to find products from database!');
        }
        if (!$categories) {
            $this->addFlash('danger', 'Unable to find categories from database!');
        }

        $sessionCart = $request->getSession();
        $cart = $sessionCart->get('cart');

        
    
        // $this->ajaxGetProductsAction($request);

        return $this->render('bakery/dumb.html.twig', [
            'products' => $products,
            'categories' => $categories,
            'cart' => $cart,
            'product' => $prodRepo

            // 'cart_size' => $cart['size'],
            // 'cart_price' => $cart['price']
        ]);
    }
}


