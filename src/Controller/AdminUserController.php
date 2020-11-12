<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminUserController extends AbstractController
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
     * @Route("/admin/list/user", name="admin.user.index")
     */
    public function index(Request $request){
      
        $users = $this->repository->findAll();
       
        return $this->render("user/index.html.twig", [
            'users' => $users
        ]);
    }


	/**
     * @Route("/admin/create/user", name="admin.create.user")
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
     * @Route("admin/edit/user/{id}-{privilege}", name="admin.user.edit")
     */

    public function edit(User $user, $privilege, Request $request){
        
        if($privilege == "editeur"){
            $user->setStatus(1);
            $user->setRoles(["ROLE_EDITEUR"]);
        }else if($privilege == "admin"){
            $user->setStatus(2);
            $user->setRoles(["ROLE_ADMIN"]);
        }else{
            $user->setStatus(0);
            $user->setRoles(["ROLE_USER"]);
        }

        $this->em->flush();
        $this->addFlash('success', 'Privilièeg Modifié');
        
        // return $this->redirectToRoute('task_success');
         return $this->redirectToRoute("admin.user.index");
           
       

        
        
     }

    /**
     * @Route("admin/delete/user/{id}", name="admin.user.delete")
     */

    public function delete(Request $request, User $user){

            $this->em->remove($user);
            $this->em->flush();
            $this->addFlash('success', 'Categorie supprimé avec succès');
        
        return $this->redirectToRoute('admin.user.index');
    }

}