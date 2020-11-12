<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategorieRepository;
use App\Entity\Categorie;
use App\Form\CategorieType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategorieController extends AbstractController
{

	private $em;
    private $repository;
  
    public function __construct(EntityManagerInterface $em, CategorieRepository $repository ){
        $this->em = $em;
        $this->repository = $repository;
      
    }
    /**
     * @Route("/editeur/list/categorie", name="editeur.categorie.index")
     */
    public function index(Request $request){
      
        $categories = $this->repository->findAll();
       
        return $this->render("categorie/index.html.twig", [
            'categories' => $categories
        ]);
    }


	/**
     * @Route("/editeur/new/categorie", name="editeur.categorie.create")
     */

    public function create(Request $request){
        $categorie = new Categorie;
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {
            $cite = $form->getData();
            
            
            $this->em->persist($categorie);
            $this->em->flush();
    
            return $this->redirectToRoute('editeur.categorie.index');
            
        }

        return $this->render('categorie/create.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("editeur/edit/categorie/{id}", name="editeur.categorie.edit")
     */

    public function edit(Categorie $categorie, Request $request){
       
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);
        
       
        if ($form->isSubmitted() && $form->isValid()) {
            $categorie = $form->getData();
            
            $this->em->flush();
            $this->addFlash('success', 'Catégorie modifié avec succès');
    
            // return $this->redirectToRoute('task_success');
            return $this->redirectToRoute("editeur.categorie.index");
           
        }

        return $this->render('categorie/edit.html.twig', [
            "form" => $form->createView(),
            "categorie" => $categorie
        ]);
     }

    /**
     * @Route("editeur/delete/categorie/{id}", name="editeur.categorie.delete")
     */

    public function delete(Request $request, Categorie $categorie){

            $this->em->remove($categorie);
            $this->em->flush();
            $this->addFlash('success', 'Categorie supprimé avec succès');
        
        return $this->redirectToRoute('editeur.categorie.index');
    }

}