<?php

namespace App\Repository;

use App\Entity\Timetracker;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Timetracker>
 *
 * @method Timetracker|null find($id, $lockMode = null, $lockVersion = null)
 * @method Timetracker|null findOneBy(array $criteria, array $orderBy = null)
 * @method Timetracker[]    findAll()
 * @method Timetracker[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimetrackerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Timetracker::class);
    }

    public function add(Timetracker $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Timetracker $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getFilteredEntries(UserInterface $user, \DateTime $from, \DateTime $to)
    {
        $queryBuilder = $this->createQueryBuilder('t');
        $queryBuilder
            ->select()
            ->where('t.user = :user')
            ->add('where', $queryBuilder->expr()->between(
                't.createdAt', ':from', ':to'
            ))
            ->setParameter('user', $user->getId())
            ->setParameters([
                'from' => $from->format('Y-m-d'),
                'to' => $to->format('Y-m-d'),
            ]);
        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }
}
