<?php

namespace App\Repository;

use App\Entity\Products;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Andx;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Node\Expr\Cast\Array_;

/**
 * @method Products|null find($id, $lockMode = null, $lockVersion = null)
 * @method Products|null findOneBy(array $criteria, array $orderBy = null)
 * @method Products[]    findAll()
 * @method Products[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Products::class);
    }


    /**
     * @return Products[] Returns an array of Products objects
     */
    public function getProducts(): Array
    {
        $em=$this->getEntityManager();
        $query = $em->createQuery('SELECT p.id, p.name, p.description,c.id as cat_id, p.price, p.image
                                    FROM app\Entity\Products p
                                    JOIN app\Entity\Categories c
                                    WHERE  p.category = c.id');
        return  $query->getResult();

        // `id`, `cat_id`, `name`, `description`, `price`, `image`
    }

        /**
     * @return Products[] Returns an array of Products objects
     */
    public function getProductsWithLimit($index, $noObjects): Array
    {
        $em=$this->getEntityManager();
        $query = $em->createQuery('SELECT p.id, p.name, p.description,c.id as cat_id, p.price, p.image
                                    FROM app\Entity\Products p
                                    JOIN app\Entity\Categories c
                                    WHERE  p.category = c.id');
        return  $query->setMaxResults($noObjects)->setFirstResult($index)->getResult();

        // `id`, `cat_id`, `name`, `description`, `price`, `image`
    }


          /**
     * @return Products[] Returns an array of Products objects
     */
    public function getProductsWithLimitCategory($index, $noObjects, $category): Array
    {
        $em=$this->getEntityManager();
        $query = $em->createQuery('SELECT p.id, p.name, p.description,c.id as cat_id, p.price, p.image
                                    FROM app\Entity\Products p
                                    JOIN app\Entity\Categories c
                                    WHERE  p.category = c.id AND
                                    p.category = :category')->setParameter('category', $category);
        return  $query->setMaxResults($noObjects)->setFirstResult($index)->getResult();

        // `id`, `cat_id`, `name`, `description`, `price`, `image`
    }


    /**
     * @return Products Returns an Product object
     */
    public function getProduct($product_id): Array{
        $em=$this->getEntityManager();
        $query = $em->createQuery('SELECT p.name, p.description,c.id as cat_id, p.price, p.image
                                    FROM app\Entity\Products p
                                    JOIN app\Entity\Categories c
                                    WHERE  p.category = c.id AND
                                    p.id = :product_id')->setParameter('product_id', $product_id);;
        $result = $query->getResult();
        return  $result[0];
    }

    

    // /**
    //  * @return Products[] Returns an array of Products objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Products
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
