<?php

namespace App\Repository\Main;

use App\Entity\Main\CInvoiceline;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CInvoiceline|null find($id, $lockMode = null, $lockVersion = null)
 * @method CInvoiceline|null findOneBy(array $criteria, array $orderBy = null)
 * @method CInvoiceline[]    findAll()
 * @method CInvoiceline[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CInvoicelineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CInvoiceline::class);
    }
}
