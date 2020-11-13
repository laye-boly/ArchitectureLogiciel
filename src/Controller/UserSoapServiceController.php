<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\UserSoapService;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Laminas\Soap\AutoDiscover;
use Laminas\Soap\Server;


class UserSoapServiceController extends AbstractController
{

  private $em;
   
    private $encoder;
  
    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder){
        $this->em = $em;
        
        $this->encoder = $encoder;

    }


    /**
     * @Route("/soap")
     */

    public function index(UserSoapService $userSoapService){
      $autodiscover = new AutoDiscover();
      $autodiscover
        ->setClass(UserSoapService::class)
        ->setUri('http://127.0.0.1:8000/index.php/soap?wsdl')
        ->setServiceName('MySoapService');

      $wsdl = $autodiscover->generate();
      $wsdl->dump("../public/webservice.wsdl");

      $soapServer = new \SoapServer('../public/webservice.wsdl');
      $soapServer->setObject($userSoapService);

      $response = new Response();
      $response->headers->set('Content-Type', 'text/xml; charset=ISO-8859-1');

      ob_start();
      $soapServer->handle();
      $response->setContent(ob_get_clean());

      return $response;
    



}

   

   

}