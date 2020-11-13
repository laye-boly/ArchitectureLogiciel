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
use Doctrine\Common\Collections\Collection;
class UserSoapService extends AbstractController
{

	private $em;
    private $verifierJetonService;
    private $encoder;
  
    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, VerifierJetonService $verifierJetonService){
        $this->em = $em;
        $this->verifierJetonService = $verifierJetonService;
        $this->encoder = $encoder;

    }

    /**
    * @param string $emailUser 
    * @return array
    */
    
    public function list(string $emailUser){

        if(!$this->verifierJetonService->verifierJeton($emailUser, "READ")){
            return array('note' => "vous n'avez pas acces à ce service");
        }
      
        $users = $this->em->getRepository(User::class)->findAll();
       
        $usersArray = array();
        foreach ($users as  $user) {
            $userArray = array();
            $userArray[] = $user->getId();
            $userArray[] = $user->getPrenom();
            $userArray[] = $user->getNom();
            $userArray[] = $user->getEmail();
            $userArray[] = $user->getRoles();
            $usersArray[] = $userArray;
        }

        return $usersArray;
    }

    /**
    * @param int $a
    * @param int $b
    * @return int
    */
    public function tr($a, $b){
        return $a+$b;
    }

    /**
    * @param string $prenom
    * @param string $nom
    * @param string $email
    * @param string $emailUser 
    * @return string 
    */
    public function create($prenom, $nom, $email, $emailUser){

        if(!$this->verifierJetonService->verifierJeton($emailUser, "CREATE")){
            return "vous n'avez pas acces à ce service";
        }        
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
    
            return "create";

        
       
    }
     /**
    * @param int $id
    * @param int $status
    * @param string $emailUser
    * @return string 
    */
    public function edit($id, $status, $emailUser){

        if(!$this->verifierJetonService->verifierJeton($emailUser, "UPD")){
            return "vous n'avez pas acces à ce service";
        }
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

       
        
         return "ok";
           
       

        
        
     }

    /**
    * @param int $id
    * @param string $emailUser 
    * @return string 
    */
   
    public function delete($id, $emailUser){

        if(!$this->verifierJetonService->verifierJeton($emailUser, "DEL")){
            return "vous n'avez pas acces à ce service";
        }
        $user = $this->em->getRepository(User::class)->find($id);
        $this->em->remove($user);
        $this->em->flush();
           
        
        return "ok";
    }

    /**
    * @param string $email
    * @param string $passe
    * @return string
    */

    public function authentificate($email, $passe){

        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);

        $passe = $this->encoder->encodePassword(
                $user,
                $passe
            );

        $users = $this->em->getRepository(User::class)->findAll();

        foreach ($users as $user) {

            if($user->getPassword() == $passe){
                return "oui";
            }
        }

        return "non";

    }

}