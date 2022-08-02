<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220802175409 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create timetracker table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE timetracker (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, time_spent VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_CA682DAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE timetracker ADD CONSTRAINT FK_CA682DAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE timetracker');
    }
}
