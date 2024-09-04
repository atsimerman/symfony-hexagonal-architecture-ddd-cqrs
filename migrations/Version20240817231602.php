<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240817231602 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE "user_email_confirmation_tokens" (id UUID NOT NULL, user_id UUID NOT NULL, token CHAR(32) NOT NULL, expires_at TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_70BC41A1A76ED395 ON "user_email_confirmation_tokens" (user_id)');
        $this->addSql('ALTER TABLE "user_email_confirmation_tokens" ADD CONSTRAINT FK_70BC41A1A76ED395 FOREIGN KEY (user_id) REFERENCES "users" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user_email_confirmation_tokens" DROP CONSTRAINT FK_70BC41A1A76ED395');
        $this->addSql('DROP TABLE "user_email_confirmation_tokens"');
    }
}
