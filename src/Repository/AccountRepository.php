<?php

namespace Ig0rbm\Memo\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\ORMException;
use Ig0rbm\Memo\Entity\Account;
use Ig0rbm\Memo\Entity\Telegram\Message\Chat;

class AccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }

    public function findOneByChat(Chat $chat): ?Account
    {
        /** @var Account $account */
        $account = $this->findOneBy(['chat' => $chat]);

        return $account;
    }

    /**
     * @throws ORMException
     */
    public function addAccount(Account $account): void
    {
        $this->getEntityManager()->persist($account);
    }
}