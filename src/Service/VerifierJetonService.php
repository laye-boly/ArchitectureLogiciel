<?php
namespace App\Service;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Jeton;


class VerifierJetonService{

	private $em;
      
    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }    

	public function verifierJeton($email, $typeService){
		
    		$jetonsAVerifier = hash("sha256", $email.''.$typeService); 
            $jetons = $this->em->getRepository(Jeton::class)->findAll();
            $acces = false;
            foreach($jetons as $jeton){
                if($jeton == $jetonsAVerifier){
                    $acces = true;
                    break;
                }
    			
    		}

            return $acces;
    }
	
}