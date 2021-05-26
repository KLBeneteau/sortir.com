<?php

namespace App\Repository;

use App\Entity\Participant;
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
              String $dateDeb,
              String $dateFin,
              bool $cb_organisateur,
              bool $cb_inscrit,
              bool $cb_pasInscrit,
              bool $cb_passer,
              Participant $curentUser,
              int $etatPasserId
        ): array
    {

        $requestSql =  $this->createQueryBuilder('s') ;

        if ($campusId!=-1) {
            $requestSql->andWhere('s.campus = :campusId')
                       ->setParameter('campusId',$campusId);
        }

        if ($nomSortie!=""){
            $requestSql->andWhere('s.nom LIKE :nom')
                       ->setParameter('nom','%'.$nomSortie.'%');
        }

        if($dateDeb!=""){
            $requestSql->andWhere('s.dateHeureDebut > :dateDeb')
                       ->setParameter('dateDeb',date_create($dateDeb));
        }

        if($dateFin!=""){
            $requestSql->andWhere('s.dateHeureDebut < :dateFin')
                ->setParameter('dateFin',date_create($dateFin));
        }

        if($cb_organisateur || $cb_inscrit || $cb_pasInscrit ||$cb_passer) {
            $orWhere = '';

            if ($cb_organisateur) {
                $orWhere .= 's.organisateur = :orga OR ' ;
            }
            /**
            if ($cb_inscrit) {
                $orWhere .= 's.participants = :userI OR ' ;
            }

            if ($cb_pasInscrit) {
                $orWhere .= 's.participants != :userPasI OR ' ;
            }
            */
            if ($cb_passer) {
                $orWhere .= 's.etat = :etat OR ' ;
            }

            $requestSql->andWhere(preg_replace('#OR $#','',$orWhere));

            if($cb_organisateur) {$requestSql->setParameter('orga', $curentUser);}
            if($cb_inscrit) {$requestSql->setParameter('userI', $curentUser);}
            if($cb_pasInscrit) {$requestSql->setParameter('userPasI', $curentUser);}
            if($cb_passer) {$requestSql->setParameter('etat', $etatPasserId);}
        }

        return $requestSql->getQuery()->getResult();
    }
}
