<?php


namespace App\tools;


use App\Repository\BadWordRepository;


class Censurator
{
    /**
     * @var BadWordRepository
     */
    private $badWordRepository;

    public function __construct(BadWordRepository $badWordRepository){
        $this->badWordRepository = $badWordRepository;
    }
    public function purify ($string){
        $listBadWord = $this->badWordRepository->findAll();
        foreach ($listBadWord as $value){
            $badWord = $value->getWord();
            $wordReplaced = mb_substr($badWord,0,1).str_repeat("*",mb_strlen($badWord)-1);
            $string = str_ireplace($badWord,$wordReplaced,$string);
        }
        return $string;
    }
}