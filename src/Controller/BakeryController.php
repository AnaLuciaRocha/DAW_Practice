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
     * @Route("/menu", name="menu")
     */
    public function menu(Request $request, ProductsRepository $prodRepo, CategoriesRepository $categories): Response
    {
        // if(!$category_id){
        //     $category_id=1;
        // }

        $products = $prodRepo->getProductsWithLimit(0, 2);

        $categories = $categories->getCategories();

        if (!$products) {
            $this->addFlash('danger', 'Unable to find products from database!');
        }
        if (!$categories) {
            $this->addFlash('danger', 'Unable to find categories from database!');
        }


        return $this->render('bakery/menu.html.twig', [
            'products' => $products,
            'categories' => $categories,
            'product' => $prodRepo
        ]);
    }

    /**
     * @Route("/menu/{category_id}", name="menuCategory")
     */
    public function menuByCategories(Request $request, $category_id, ProductsRepository $prodRepo, CategoriesRepository $categories): Response
    {
        
        $products = $prodRepo->getProductsWithLimitCategory(0, 2, $category_id);
        $categories = $categories->getCategories();

        $category_id = $request->attributes->get('category_id');

        if (!$products) {
            $this->addFlash('danger', 'Unable to find products from database!');
        }
        if (!$categories) {
            $this->addFlash('danger', 'Unable to find categories from database!');
        }


        return $this->render('bakery/menu.html.twig', [
            'products' => $products,
            'categories' => $categories,
            'product' => $prodRepo,
            'category_id' => $category_id
        ]);
    }


    /**
     * @Route("/ajax/{category_id}/{index}", name="ajax")
     */
    public function ajaxGetProductsAction(Request $request, int $category_id, int $index, ProductsRepository $prodRepo, CategoriesRepository $categories): Response
    {

        
        $products = $prodRepo->getProductsWithLimitCategory($index, 2, $category_id);
        // $products = $prodRepo->getProductsWithLimit($index, 2);
        
        // dump($category_id, $products);
        // die();

        // var_dump($products[0]->getCategory()->getId());
        if (!$products) {
            $response = new JsonResponse(['data' => null]);
            return $response;
            // $this->addFlash('danger', 'Unable to find products from database!');
        }


        return $this->render('bakery/dumb.html.twig', [
            'products' => $products
        ]);
    }


    /**
     * @Route("/order/{id}", name="placeOrder")
     */
    function order(Request $request, int $id, ProductsRepository $prodRepo)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        /** @var \App\Entity\User $user */
        $user = $this->getUser();



        $entityManager = $this->getDoctrine()->getManager();
        //1) create order object
        $order = new Orders();
        $order->setUser($user);
        $date = new \DateTime('@' . strtotime('now'));
        $order->setCreatedAt($date);
        //Get product ordered
        $orderProduct = $this->getDoctrine()
            ->getRepository(Products::class)
            ->find($id);

        $order->setProduct($orderProduct);


        $entityManager->persist($order);
        // 3) create order on the database
        $entityManager->flush();


        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        $this->addFlash('success', 'Thank you for your order!');


        return $this->redirectToRoute('menu');
    }


    /**
     * @Route("/orders", name="myOrders")
     */
    public function orders(Request $request, OrdersRepository $orders)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        //Get session cart
        $sessionCart = $request->getSession();
        $cart = $sessionCart->get('cart');

        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        //Get orders from database
        $orders =  $this->getDoctrine()
            ->getRepository(Orders::class)
            ->findAll();

        // dump($orders);
        // die();

        // //Find order items for each order 
        // foreach ($orders as &$order) {
        //     $orderItems[$order["id"]] = $items->getOrderItems($order["id"]);
        // }

        return $this->render('bakery/myOrders.html.twig', [
            'orders' => $orders
        ]);
    }
}
