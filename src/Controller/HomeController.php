<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ArticleRepository;
use App\Entity\Article;
use App\Entity\Categorie;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;


class HomeController extends AbstractController
{

	private $em;
    private $repository;
  
    public function __construct(EntityManagerInterface $em, ArticleRepository $repository, PaginatorInterface $paginator ){
        $this->em = $em;
        $this->repository = $repository;
        $this->paginator = $paginator;
      
    }
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request){
       
       $articles = $this->repository->findAll();
       $categories = $this->em->getRepository(Categorie::class)->findAll();
       // dd($articles);
        $articles = $this->paginator->paginate(
            $articles,
            $request->query->getInt('page', 1),
            3 //limite par page
        );
       // dd($articles);
        return $this->render("base.html.twig", [
            'articles' => $articles,
            'categories' => $categories
        ]);

       
       
    }

    /**
     * @Route("/articles/{nomCategorie}", name="home.categorie")
     */
    public function indexCategorie(Request $request, $nomCategorie){
       $categoriesParArticles = $this->em->getRepository(Categorie::class)->findOneBy(['nom' => $nomCategorie]);
       
       $articles = $categoriesParArticles->getArticles();
       // dd($articles);
        $articles = $this->paginator->paginate(
            $articles,
            $request->query->getInt('page', 1),
            3 //limite par page
        );
        $categories = $this->em->getRepository(Categorie::class)->findAll();
       // dd($articles);
        return $this->render("base.html.twig", [
            'articles' => $articles,
            'categories' => $categories
        ]);

       
       
    }

}