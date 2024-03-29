<?php

namespace App\Repository\Main;

use App\Entity\Main\AdUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AdUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdUser[]    findAll()
 * @method AdUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method array       findOrgs(int $ad_user_id)
 */
class AdUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdUser::class);
    }

    /**
     * Buscar organizaciones asignadas a 
     * un usuario
     * 
     * @param int $ad_user_id
     * 
     * @return array Organizaciones
     */
    public function findOrgs(int $ad_user_id)
    {
        $connection = $this->getEntityManager()->getConnection();

        $sql = 
            "SELECT ao.*
            FROM AD_User_OrgAccess auo
            JOIN AD_Org ao ON ao.AD_Org_ID = auo.AD_Org_ID
            WHERE auo.AD_Org_ID > 0 AND auo.Isactive = 'Y' AND ao.IsSelected = 'N' AND ao.Issummary = 'N' AND auo.AD_User_ID = :user_id";
        $stmt = $connection->prepare($sql);
        $resulSet = $stmt->executeQuery(['user_id' => $ad_user_id]);

        return $resulSet->fetchAllAssociative();
    }
    
    /**
     * Buscar roles asignados a un usuario
     * 
     * @param int $ad_user_id
     * 
     * @return array Roles
     */
    public function findRoles(int $ad_user_id)
    {
        $connection = $this->getEntityManager()->getConnection();

        $sql = 
            "SELECT ar.*
            FROM AD_User_Roles aur
            JOIN AD_Role ar ON ar.AD_Role_ID = aur.AD_Role_ID
            WHERE aur.IsActive = 'Y' AND aur.AD_User_ID = :user_id
            ORDER BY ar.AD_Role_ID ASC";
        $stmt = $connection->prepare($sql);
        $resulSet = $stmt->executeQuery(['user_id' => $ad_user_id]);

        return $resulSet->fetchAllAssociative();
    }
    
    /**
     * Busca los representantes comerciales
     * 
     * @param string $name Nombre del vendedor
     * 
     * @return array Vendedores
     */
    public function findSalesrep(String $name = '')
    {
        $qb = $this->createQueryBuilder('au');

        $query = $qb
            ->join('au.c_bpartner', 'cb')
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('au.isactive', "'Y'"),
                    $qb->expr()->eq('cb.isactive', "'Y'"),
                    $qb->expr()->eq('cb.issalesrep', "'Y'"),
                    $qb->expr()->like('au.name', ':name')
                )
            )
            ->setParameters(
                new ArrayCollection([
                    new Parameter('name', "%". $name ."%")
                ])
            )
            ->setMaxResults(4)
            ->getQuery();

        return $query->getResult();
    }
}
