<?php

namespace App\Repository\Main;

use App\Entity\Main\AdSequence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AdSequence|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdSequence|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdSequence[]    findAll()
 * @method AdSequence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdSequenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdSequence::class);
    }
    
    /**
     * Buscar el correlativo siguiente de una
     * secuencia
     * 
     * @param AdSequence $sequence Secuencia
     * @param object $entity Entidad base
     * 
     * @return string Correlativo 
     */
    public function findNextSequence(AdSequence $sequence, Object $entity = null): String
    {
        $connection = $this->getEntityManager()->getConnection();

        if( $sequence->getIsorglevelsequence() )
            return $this->findNextSequenceByOrg($sequence, $entity);

        $stmt = $connection->prepare("SELECT nextid(:sequence_id, 'N')");
        $resultSet = $stmt->executeQuery(['sequence_id' => $sequence->getId() ]);

        return $resultSet->fetchOne();
    }

    /**
     * Buscar el correlativo siguiente 
     * de una secuencia correspondiente a 
     * una organizacion 
     * 
     * @param AdSequence $sequence Secuencia
     * @param object $entity Entidad base
     * 
     * @return string Correlativo
     */
    protected function findNextSequenceByOrg(AdSequence $sequence, Object $entity): String
    {
        $connection = $this->getEntityManager()->getConnection();

        // Get Current Next
        $stmt = $connection->prepare(
            "SELECT currentnext FROM ad_sequence_no 
            WHERE ad_sequence_id = :sequence_id AND ad_org_id = :org_id");
        $resultSet = $stmt->executeQuery([
            'sequence_id' => $sequence->getId(),
            'org_id' => $entity->getAdOrgId()
        ]);
        $currentnext = $resultSet->fetchOne();

        // Get Prefix
        $prefix = $this->buildPrefex($sequence->getPrefix(), $entity);

        // Update Sequence No
        $stmt = $connection->prepare(
            "UPDATE ad_sequence_no SET currentnext = :currentnext
            WHERE ad_sequence_id = :sequence_id AND ad_org_id = :org_id"
        );
        $stmt->executeQuery([
            'currentnext' => $currentnext + 1,
            'sequence_id' => $sequence->getId(),
            'org_id' => $entity->getAdOrgId()
        ]);

        return $prefix . $currentnext;
    }

    /**
     * Genera un RowGUID
     * 
     * @return string RowGUID
     */
    public function findNextUU(): String
    {
        $connection = $this->getEntityManager()->getConnection();

        $stmt = $connection->prepare("SELECT generate_uuid()");
        $resultSet = $stmt->executeQuery();

        return $resultSet->fetchOne();
    }

    /**
     * Construir prefijos de secuencias
     * 
     * @param string $prefix Prefijo de la secuencia
     * @param object $entity Entidad base
     * 
     * @return string Prefijo
     */
    protected function buildPrefex(String $prefix, Object $entity): String
    {
        if ( !empty($prefix) ) {
            $prefix = preg_split('([<@.>\s])', $prefix);
            $prefix = array_filter($prefix, 'trim');

            $_prefix = $prefix[0];
            if (count($prefix) === 4) {
                $connection = $this->getEntityManager()->getConnection();
                $getValueId = 'get' . implode( array_map('ucfirst', explode('_', $prefix[1]) ) );

                $sql = "SELECT {$prefix[3]} FROM {$prefix[2]} WHERE {$prefix[1]} = :value";
                $stmt = $connection->prepare($sql);
                $resultSet = $stmt->executeQuery( ['value' => $entity->$getValueId()] );

                $_prefix .= $resultSet->fetchOne();
            }
            return $_prefix;
        }
        return '';
    }
}
