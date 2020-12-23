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
}
