<?php

namespace App\Repository;

use App\Entity\Brand;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class BrandRepository extends ServiceEntityRepository
{
    public function __construct(\Doctrine\Persistence\ManagerRegistry $registry)
    {
        parent::__construct($registry, \App\Entity\Brand::class);
    }

    public function save(Brand $brand, bool $flush = false): void
    {
        $this->getEntityManager()->persist($brand);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}