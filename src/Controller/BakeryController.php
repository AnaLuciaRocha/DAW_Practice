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

    $products = $prodRepo->getProductsWithLimit($index,2);
    

        // var_dump($products[0]->getCategory()->getId());

        if (!$products) {
            $this->addFlash('danger', 'Unable to find products from database!');
        }
      

        $sessionCart = $request->getSession();
        $cart = $sessionCart->get('cart');

        
    
        // $this->ajaxGetProductsAction($request);

        return $this->render('bakery/dumb.html.twig', [
            'products' => $products
        ]);
    }


    /**
     *  @Route("/add_product/{id}", name="addProduct")
     */
    public function addProduct(Request $request, int $id)
    {
        // getting session 
        $sessionCart = $request->getSession();

        $cart = $sessionCart->get('cart');

        //Create cart
        if ($cart == null) {
            $cart = []; // creates an array
        }

        //Set Quantity for each product
        if (!isset($cart[$id])) { //if itemID isnt in the cart
            $cart["$id"] = $id;
            $quantity = 0;
        } else {
            $quantity = $cart["$id"]["quantity"];
        }

        //set quantity for each product, by default is 1
        $cart["$id"] = array("itemID" => $id, "quantity" => $quantity + 1);

        $sessionCart->set('cart', $cart);

        return $this->redirectToRoute('menu');
    }
    
    /**
     * @Route("/order/{id}", name="placeOrder")
     */
    function order(Request $request, int $id, ProductsRepository $prodRepo){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        
        
        
        $entityManager = $this->getDoctrine()->getManager();
        //1) create order object
        $order = new Orders();
        $order->setUser($user);
        $date = new \DateTime('@' . strtotime('now'));
        $order->setCreatedAt($date);
        $order -> setStatus("Finished");
        $order -> setTotal($prodRepo->getProduct($id)["price"]);
        $entityManager->persist($order);
        // 3) create order on the database
        $entityManager->flush();


            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();
        
        $this->addFlash('success', 'Thank you for your order!');
        

        return $this->redirectToRoute('menu');
    }

}


