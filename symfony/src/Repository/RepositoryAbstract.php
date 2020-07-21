<?php
namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * Class RepositoryAbstract
 * @package App\Repository
 */
abstract class RepositoryAbstract extends ServiceEntityRepository implements RepositoryInterface
{
    /**
     * @var string
     */
    protected $class;

    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var string
     */
    private $table;
    /**
     * @var EntityManager|EntityManagerInterface
     */
    private $em;

    public function __construct(ManagerRegistry $registry)
    {
        $this->class = $this->entity();
        parent::__construct($registry, $this->class);
        $this->loadConnection();
    }

    /**
     * @return void
     */
    private function loadConnection()
    {
        $this->em = $this->getEntityManager();
        $this->connection = $this->em->getConnection();
        $this->table = $this->em->getClassMetadata($this->class)->getTableName();
    }

    /**
     * @return void
     * @throws DBALException
     */
    public function truncate()
    {
        $this->connection->query('SET FOREIGN_KEY_CHECKS=0');
        $platform = $this->connection->getDatabasePlatform();
        $this->connection->executeUpdate($platform->getTruncateTableSQL($this->table, true ));
        $this->connection->query('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * @param array $data
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function insert(array $data)
    {
        $obj = new $this->class();
        foreach ($data as $attribute => $value ) {
            $attribute_set = 'set' . ucwords($attribute);
            $obj->$attribute_set($value);
        }
        $this->em->persist($obj);
        $this->em->flush();

        return $obj;
    }

    /**
     * @return array|object[]
     */
    public function all(): array
    {
        $repository = $this->em->getRepository($this->class);
        return $repository->findAll();
    }

    /**
     * @return string
     */
    abstract protected function entity(): string ;
}