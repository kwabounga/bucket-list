<?php

namespace App\Repository;

use App\Entity\Idea;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
/**
 * @method Idea|null find($id, $lockMode = null, $lockVersion = null)
 * @method Idea|null findOneBy(array $criteria, array $orderBy = null)
 * @method Idea[]    findAll()
 * @method Idea[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IdeaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Idea::class);
    }

    // /**
    //  * @return Idea[] Returns an array of Idea objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Idea
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function random(EntityManagerInterface $em)
    {
        $rsm = new ResultSetMappingBuilder($em);
        $rsm->addRootEntityFromClassMetadata(Idea::class, 'i');
        $rsm->addEntityResult( Idea::class, 'i');
        $rsm->addFieldResult('i', 'title', 'title');
        $rsm->addFieldResult('i', 'author', 'author');
        $rsm->addFieldResult('i', 'date_created', 'dateCreated');
        $rsm->addFieldResult('i', 'id', 'id');

        $sql = $this->getEntityManager()->createNativeQuery("SELECT * FROM `idea` ORDER BY RAND() LIMIT 1;", $rsm);

//        dump($sql);
        $rlt = $sql->getResult();
//        dump($sql);

//        dump($rlt);
        return $rlt;

    }

    public function search($title) {
        return $this->createQueryBuilder('Idea')
            ->andWhere('Idea.title LIKE :title')
            ->setParameter('title', '%'.$title.'%')

            ->leftJoin('Idea.category', 'cat')
            ->addSelect('cat')
            ->orWhere('cat.name LIKE :cate')
            ->setParameter('cate', '%'.$title.'%')

            ->orWhere('Idea.author LIKE :aut')
            ->setParameter('aut', '%'.$title.'%')

            ->orderBy('Idea.category', 'DESC')
            ->getQuery()
            ->execute();
    }

    public function all($limit, $offset, EntityManagerInterface $em) {

        $rsm = new ResultSetMappingBuilder($em);
        $rsm->addRootEntityFromClassMetadata(Idea::class, 'i');
        $rsm->addEntityResult( Idea::class, 'i');
        $rsm->addFieldResult('i', 'id', 'id');
        $rsm->addFieldResult('i', 'title', 'title');
        $rsm->addFieldResult('i', 'author', 'author');
        $rsm->addFieldResult('i', 'description', 'description');
        $rsm->addFieldResult('i', 'dateCreated', 'dateCreated');

        $sql = $this->getEntityManager()->createNativeQuery("SELECT * FROM `idea` LIMIT ".$limit." OFFSET ".$offset.";", $rsm);

//        dump($sql);
        $rlt = $sql->getResult();
        return $rlt;

    }
}
