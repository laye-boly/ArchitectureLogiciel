<?php
namespace App\Service;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Article;


class PrevNextService {

	private $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }

    public function next(Article $article){
    	
    	$id = $article->getId();
    	$i = 0;
    	$next = null;
    	if($id !== $this->em->getRepository(Article::class)->derniereArticle()[0]->getId()){

    		while($next == null){
    			$i++;
    			$next = $this->em->getRepository(Article::class)->find($id+$i);
    		}
    	}	
    	return $next;
    }

    public function previous(Article $article){
    	
    	$id = $article->getId();
    	$i = 0;
    	$previous = null;
    	if($id !== $this->em->getRepository(Article::class)->premiereArticle()[0]->getId()){
    		while($previous == null){
    			$i++;
    			$previous = $this->em->getRepository(Article::class)->find($id-$i);
    		}
    	}
    	return $previous;
    }


}