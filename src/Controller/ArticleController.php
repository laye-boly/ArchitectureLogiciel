<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ArticleRepository;
use App\Entity\Article;
use App\Entity\User;
use App\Form\ArticleType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\PrevNextService;

class ArticleController extends AbstractController
{

	private $em;
    private $repository;
  
    public function __construct(EntityManagerInterface $em, ArticleRepository $repository ){
        $this->em = $em;
        $this->repository = $repository;
      
    }
    /**
     * @Route("/editeur/list/article", name="editeur.article.index")
     */
    public function index(Request $request){
      
        $articles = $this->repository->findAll();
       
        return $this->render("article/index.html.twig", [
            'articles' => $articles
        ]);
    }


	/**
     * @Route("/editeur/new/article", name="editeur.article.create")
     */

    public function create(Request $request){
        $article = new Article;
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $userRepository = $this->em->getRepository(User::class);
            $user = $userRepository->find(1);
            $article->setUser($this->getUser());
            $this->em->persist($article);
            $this->em->flush();
    
            return $this->redirectToRoute('editeur.article.index');
            
        }

        return $this->render('article/create.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("editeur/edit/article/{id}", name="editeur.article.edit")
     */

    public function edit(Article $article, Request $request){
       
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        
       
        if ($form->isSubmitted() && $form->isValid()) {
            $categorie = $form->getData();
            
            $this->em->flush();
            $this->addFlash('success', 'Article modifié avec succès');
    
            // return $this->redirectToRoute('task_success');
            return $this->redirectToRoute("editeur.article.index");
           
        }

        return $this->render('article/edit.html.twig', [
            "form" => $form->createView(),
            "article" => $article
        ]);
     }

     /**
     * @Route("editeur/show/article/{id}", name="editeur.show.article")
     */

    public function show(Request $request, Article $article, PrevNextService $prevNext){

            
        $articleSuivant = $prevNext->next($article);
        
        $articlePrecedent = $prevNext->previous($article);
        return $this->render('article/show.html.twig', [
                    "article" => $article,
                    "articleSuivant" => $articleSuivant,
                    "articlePrecedent" => $articlePrecedent
        ]);
    }


    /**
     * @Route("editeur/delete/article/{id}", name="editeur.article.delete")
     */

    public function delete(Request $request, Article $article){

            $this->em->remove($article);
            $this->em->flush();
            $this->addFlash('success', 'Article supprimé avec succès');
        
        return $this->redirectToRoute('editeur.article.index');
    }

}