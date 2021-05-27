<?php

namespace App\Repository;

use App\Entity\Participant;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

    public function recherchesSorties(
              int $campusId,
              String $nomSortie,
              String $dateDeb,
              String $dateFin,
              bool $cb_organisateur,
              bool $cb_inscrit,
              bool $cb_pasInscrit,
              bool $cb_passer,
              bool $cb_annuler,
              Participant $curentUser
        ): array
    {

        $requestSql =  $this->createQueryBuilder('s')
                            ->innerJoin("s.etat",'e')->addSelect('e')
                            ->orderBy('s.dateHeureDebut');

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

        $whereEtat = '';

        if ($cb_passer) {
             $whereEtat .= "e.libelle = 'Passée' " ;
        } else {
             $whereEtat .= "e.libelle != 'Passée' " ;
        }
        if ($cb_passer && $cb_annuler) $whereEtat .= 'OR ' ; else $whereEtat .= 'AND ';
        if ($cb_annuler) {
             $whereEtat .= "e.libelle = 'Annulée'" ;
        } else {
             $whereEtat .= "e.libelle != 'Annulée'" ;
        }

        $requestSql->andWhere(preg_replace('#OR $|AND $#','',$whereEtat));

        if($cb_organisateur || $cb_inscrit || $cb_pasInscrit) {
            $orWhere = '';

            if ($cb_organisateur) {
                $orWhere .= 's.organisateur = :orga OR ' ;
            }


            $TableauInscription = implode(',',$curentUser->getInscriptions()->toArray())  ;
            if($TableauInscription=""){
                $TableauInscription= '-1' ;
            }

            if ($cb_inscrit) {
                $orWhere .= 's.id IN (:listInscriptionUser) OR ' ;
            }

            if ($cb_pasInscrit) {
                $orWhere .= 's.id NOT IN (:listInscriptionUser2) OR ' ;
            }


            $requestSql->andWhere(preg_replace('#OR $#','',$orWhere));

            if($cb_organisateur) {$requestSql->setParameter('orga', $curentUser);}
            if($cb_inscrit) {$requestSql->setParameter('listInscriptionUser', $TableauInscription);}
            if($cb_pasInscrit) {$requestSql->setParameter('listInscriptionUser2', $TableauInscription);}
        }

        dump($requestSql->getQuery());

        return $requestSql->getQuery()->getResult();
    }
}
