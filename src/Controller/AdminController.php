<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{

	   

    /**
     * @Route("/administration", name="admin.index")
     */

    public function index(Request $request){
      
               
        return $this->render("admin/index.html.twig");
    }


   

}