<?php
// src/Controller/LuckyController.php
namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserSoapService extends AbstractController
{

	private $em;
   
    private $encoder;
  
    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder){
        $this->em = $em;
        
        $this->encoder = $encoder;

    }

       public function list(){
      
        $users = $this->em->getRepository(User::class)->findAll();
       
        return $users;
    }

    public function test($a, $b){
        return $a+$b;
    }
    public function create($prenom, $nom, $email){
        var_dump("laye");
        die();
        $user = new User;
        $user->setPrenom($prenom);
        $user->setNom($nom);
        $user->setEmail($email);
        $user->setPassword("test");
        
        $user->setPassword($this->encoder->encodePassword(
            $user,
            $user->getPassword()
        ));
            $user->setStatus(0);
            
            $this->em->persist($user);
            $this->em->flush();
    
            return "ok";

        
       
    }
    public function edit($id, $status){
       $user = $this->em->getRepository(User::class)->find($id);
        if($status == 1){
            $user->setStatus(1);
            $user->setRoles(["ROLE_EDITEUR"]);
        }else if($status == 2){
            $user->setStatus(2);
            $user->setRoles(["ROLE_ADMIN"]);
        }else{
            $user->setStatus(0);
            $user->setRoles(["ROLE_USER"]);
        }

        $this->em->flush();
        $this->addFlash('success', 'Privilièeg Modifié');
        
        // return $this->redirectToRoute('task_success');
         return "ok";
           
       

        
        
     }

   
    public function delete($id){
        dd("laye");
        $user = $this->em->getRepository(User::class)->find($id);
        $this->em->remove($user);
        $this->em->flush();
           
        
        return $id;
    }

}