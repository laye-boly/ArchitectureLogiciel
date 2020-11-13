<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Form\UserType;
use App\Entity\Jeton;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{

	private $em;
    private $repository;
    private $encoder;
  
    public function __construct(EntityManagerInterface $em, UserRepository $repository, UserPasswordEncoderInterface $encoder){
        $this->em = $em;
        $this->repository = $repository;
        $this->encoder = $encoder;

    }
   

    /**
     * @Route("/new/user/compte", name="user.compte.create")
     */

    public function create(Request $request){
        $user = new User;
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            if($user->getPassword() !== $user->getPasswordconf()){
               
                $this->addFlash('error', 'Vos mots de passe sont différents');
                return $this->redirectToRoute('user.compte.create');

            }

            $user->setPassword($this->encoder->encodePassword(
                $user,
                $user->getPassword()
            ));
            $user->setStatus(0);
            
            $this->em->persist($user);
            $this->em->flush();
    
            return $this->redirectToRoute('admin.user.index');
            
        }

        return $this->render('user/create.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/user/compte{id}", name="user.compte.update")
     */

    public function edit(Request $request, User $user){
        
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            if($user->getPassword() !== $user->getPasswordconf()){
               
                $this->addFlash('error', 'Vos mots de passe sont différents');
                return $this->redirectToRoute('user.compte.update');

            }

            $user->setPassword($this->encoder->encodePassword(
                $user,
                $user->getPassword()
            ));
                       
            
            $this->em->flush();
    
            return $this->redirectToRoute('admin.user.index');
            
        }

         $jetons = $this->em->getRepository(Jeton::class)->findOneBy(['user' => $user]);
         // dd($jetons);
        return $this->render('user/edit.html.twig', [
            "form" => $form->createView(),
            "jetons" => $jetons
        ]);
    }

   

}