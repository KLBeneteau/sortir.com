<?php

namespace App\Repository;

use App\Entity\Sortie;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    /**
     * Fonction qui récupère les sorties sans une recherche.
     * @return Sortie[]
     */
    public function recherchesSorties(
              int $campusId,
              String $nomSortie,
              DateTime $dateDeb,
              DateTime $dateFin,
              bool $cb_organisateur,
              bool $cb_inscrit,
              bool $cb_pasInscrit,
              bool $cb_passer): array
    {

        $requestSql =  $this->createQueryBuilder('s')->where('true = true ') ;

        if ($campusId!=-1) {
            $requestSql->andWhere('s.campus = :campusId')
                       ->setParameter('campusId',$campusId);
        }

        if ($nomSortie!=""){
            $requestSql->andWhere('s.nom LIKE :nom')
                       ->setParameter('nom','%'.$nomSortie.'%');
        }

        return $requestSql->getQuery()->getResult();
    }
}
