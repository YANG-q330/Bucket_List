<?php


namespace App\tools;


use App\Entity\BadWord;
use Doctrine\ORM\EntityManagerInterface;

class Censurator
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    public function purify ($string){
        $badWordRepository = $this->entityManager->getRepository(BadWord::class);
        $listBadWord = $badWordRepository->findAll();
        foreach ($listBadWord as $value){
            if (strpos($string, $value->getWord())){
                $wordReplaced = str_repeat("*",mb_strlen($value->getWord()));
                $string = str_ireplace($value->getWord(),$wordReplaced,$string);
            }
        }
        return $string;
    }
}