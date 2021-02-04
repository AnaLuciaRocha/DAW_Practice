<?php

namespace  App\Handler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;



class LogoutSuccessHandler implements LogoutSuccessHandlerInterface
{
   
    private $router;
    private $tokenStorage;
    // private $securityContext;

    public function __construct(RouterInterface $router, TokenStorageInterface $tokenStorage)
    {
        $this->router = $router;
        $this->tokenStorage = $tokenStorage;
    }
    

    /**
     * {@inheritdoc}
     */
    public function onLogoutSuccess(Request $request) 
    {
        $referer = $request->headers->get('referer');
        $username = $this->tokenStorage->getToken()->getUser()->getName(); 

        $request->getSession()->getFlashBag()->add('success',
                                                    'Goodbye! '.  $username .' Login to continue shopping');


        return new RedirectResponse($referer);
    }
}