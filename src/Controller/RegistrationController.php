<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\ProductsRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, ProductsRepository $prodRep): Response
    {
        // //Get session cart
        // $sessionCart = $request->getSession();
        // $cart = $sessionCart->get('cart');
        
        // 1) build the form
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

    

        // 2) handle the submit (will only happen on POST)
        // if ($form->isSubmitted() && $form->isValid()) {
        if ($form->isSubmitted()) {

            $date = new \DateTime('@' . strtotime('now'));
            $user->setCreatedAt($date);
            $user->setUpdatedAt($date);

            if ($form->isValid()) {

                // encode the plain password
                // 3) Encode the password (you could also do this via Doctrine listener)
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

                //activation token for forgot password
                $user->setActivationDigest(md5(uniqid()));
                // 4) save the User!
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                // do anything else you need here, like send an email
                // maybe set a "flash" success message for the user

                $this->addFlash(
                    'success',
                    'Your registration is done! Please login to buy our photos'
                );

                return $this->redirectToRoute('bakery');
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            // 'cart' => $cart,
            // 'product' => $prodRep
        ]);
    }
}
