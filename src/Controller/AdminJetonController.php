<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\JetonRepository;
use App\Entity\Jeton;
use App\Form\JetonType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AdminJetonController extends AbstractController
{

    private $em;
    private $repository;
   
    public function __construct(EntityManagerInterface $em, JetonRepository $repository){
        $this->em = $em;
        $this->repository = $repository;
       

    }
    /**
     * @Route("/admin/list/jeton", name="admin.jeton.index")
     */
    public function index(){
      
        $jetons = $this->repository->findAll();
       
        return $this->render("jeton/index.html.twig", [
            'jetons' => $jetons
        ]);
    }


    /**
     * @Route("/admin/create/jeton", name="admin.create.jeton")
     */

    public function create(Request $request){

        $jeton = new Jeton;
        $form = $this->createForm(JetonType::class, $jeton);
        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {
            $jetonAHacher = $jeton->getUser()->getEmail().''.$jeton->getType();
            $jetonHache = hash("sha256", $jetonAHacher);
            $jeton->setNom($jetonHache);
            $this->em->persist($jeton);
            $this->em->flush();
    
            return $this->redirectToRoute('admin.jeton.index');
            
        }

        return $this->render('jeton/create.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("admin/delete/jeton/{id}", name="admin.jeton.delete")
     */

    public function delete(Request $request, Jeton $jeton){

            $this->em->remove($jeton);
            $this->em->flush();
            $this->addFlash('success', 'Jeton supprimé avec succès');
        
        return $this->redirectToRoute('admin.jeton.index');
    }


}