<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ArticleRepository;
use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class ServiceRestController extends AbstractController
{

	private $em;
    private $repository;
    private $serializer;
  
    public function __construct(EntityManagerInterface $em, ArticleRepository $repository, SerializerInterface $serializer){
        $this->em = $em;
        $this->repository = $repository;
      	$this->serializer = $serializer;
    } 

    /**
     * @Route("/articles", name="liste_article")
     */

    public function list(Request $request){
    	$categorie = $request->query->get('categorie');
    	$articlesTab = array();
    	// dd($categorie);
    	if($categorie !== null ){
    		if($categorie == "all" ){
    			//articles regroupé en catégorie
    			$categories = $this->em->getRepository(Categorie::class)->findAll();
          foreach ($categories as $categorie) {
            $articles = $categorie->getArticles();
            if(!$articles->isEmpty()){
              foreach ($articles as $article) {
                $articleTab['id'] = $article->getId();
                $articleTab['titre'] = $article->getTitre();
                $articleTab['contenu'] = $article->getContenu();
                $articleTab['prenomAuteur'] = $article->getUser()->getPrenom();
                $articleTab['nomAuteur'] = $article->getUser()->getNom();
                $articleTab['EmailAuteur'] = $article->getUser()->getEmail();
                $articlesTab[] = $articleTab;
              }
            }
          }
    		}else if($categorie !== null && $categorie !== "all"){
    			//articles appartenant à une categorie fourni par le user
    			$categorie = $this->em->getRepository(Categorie::class)->findOneBy(['nom' => $categorie]);
    			$articles = $categorie->getArticles();

          if(!$articles->isEmpty()){
              foreach ($articles as $article) {
                $articleTab['id'] = $article->getId();
                $articleTab['titre'] = $article->getTitre();
                $articleTab['contenu'] = $article->getContenu();
                $articleTab['prenomAuteur'] = $article->getUser()->getPrenom();
                $articleTab['nomAuteur'] = $article->getUser()->getNom();
                $articleTab['EmailAuteur'] = $article->getUser()->getEmail();
                $articlesTab[] = $articleTab;
              }
            }
    		}
    	}else {
        // Tous les articles
    		$articles = $this->repository->findAll();
        if($articles){
              foreach ($articles as $article) {
                $articleTab['id'] = $article->getId();
                $articleTab['titre'] = $article->getTitre();
                $articleTab['contenu'] = $article->getContenu();
                $articleTab['prenomAuteur'] = $article->getUser()->getPrenom();
                $articleTab['nomAuteur'] = $article->getUser()->getNom();
                $articleTab['EmailAuteur'] = $article->getUser()->getEmail();
                $articlesTab[] = $articleTab;
              }
            }
    	}
    	
       
       
       // dd($articles);
      
        
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $defaultContext = [
    		AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
        return $object->getId();
    		},
		];
		$normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
		$this->serializer = new Serializer([$normalizer], $encoders);

        if($request->query->get('format') == "json"){

          $data =  $this->serializer->serialize($articlesTab, 'json',  [
             'enable_max_depth' => true]);
          $response = new Response($data);
          $response->headers->set('Content-Type', 'application/json');
        }
        else{
        	$data =  $this->serializer->serialize($articlesTab, 'xml');

          $response = new Response($data);
          $response->headers->set('Content-Type', 'application/xml');
        }

        return $response;
    }


   

}