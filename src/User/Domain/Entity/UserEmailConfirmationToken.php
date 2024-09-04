<?php

declare(strict_types=1);

namespace App\User\Domain\Entity;

use Carbon\CarbonImmutable;

class UserEmailConfirmationToken
{
    private ?string $id                    = null;
    private ?string $token                 = null;
    private ?\DateTimeImmutable $expiresAt = null;
    private ?\DateTimeImmutable $createdAt;
    private User $user;

    public function __construct()
    {
        $this->createdAt = new CarbonImmutable();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(\DateTimeImmutable $expiresAt): static
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
