<?php

declare(strict_types=1);

namespace Ig0rbm\Memo\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="licenses")
 * @ORM\Entity(repositoryClass="Ig0rbm\Memo\Repository\LicenseRepository")
 */
final class License
{
    public const DEFAULT_TERM = 6;

    public const PROVIDER_DEFAULT = 'memo';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     *
     * @Assert\NotBlank
     * @Assert\Type("integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="datetime_immutable")
     *
     * @Assert\NotBlank
     * @Assert\DateTime()
     */
    private DateTimeImmutable $dateStart;

    /**
     * @ORM\Column(type="datetime_immutable")
     *
     * @Assert\NotBlank
     * @Assert\DateTime()
     */
    private DateTimeImmutable $dateEnd;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank
     * @Assert\Type("string")
     */
    private string $provider;

    /**
     * @ORM\ManyToOne(targetEntity="Account")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id")
     */
    private Account $account;

    public function __construct(DateTimeImmutable $dateStart, DateTimeImmutable $dateEnd, string $provider)
    {
        $this->dateStart = $dateStart;
        $this->dateEnd   = $dateEnd;
        $this->provider  = $provider;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getDateStart(): DateTimeImmutable
    {
        return $this->dateStart;
    }

    public function setDateStart(DateTimeImmutable $dateStart): void
    {
        $this->dateStart = $dateStart;
    }

    public function getDateEnd(): DateTimeImmutable
    {
        return $this->dateEnd;
    }
    public function setDateEnd(DateTimeImmutable $dateEnd): void
    {
        $this->dateEnd = $dateEnd;
    }

    public function getProvider(): string
    {
        return $this->provider;
    }

    public function setProvider(string $provider): void
    {
        $this->provider = $provider;
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function setAccount(Account $account): void
    {
        $this->account = $account;
    }
}
