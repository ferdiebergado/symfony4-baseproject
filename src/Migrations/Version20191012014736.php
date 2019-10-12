<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191012014736 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE ext_log_entries');
        $this->addSql('ALTER TABLE user CHANGE created_by created_by INT DEFAULT NULL, CHANGE updated_by updated_by INT DEFAULT NULL, CHANGE roles roles JSON NOT NULL, CHANGE verification_token verification_token VARCHAR(255) DEFAULT NULL, CHANGE verified_at verified_at DATETIME DEFAULT NULL, CHANGE is_active is_active TINYINT(1) DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ext_log_entries (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(8) NOT NULL COLLATE utf8mb4_unicode_ci, logged_at DATETIME NOT NULL, object_id VARCHAR(64) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, object_class VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, version INT NOT NULL, data LONGTEXT DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:array)\', username VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, INDEX log_date_lookup_idx (logged_at), INDEX log_class_lookup_idx (object_class), INDEX log_version_lookup_idx (object_id, object_class, version), INDEX log_user_lookup_idx (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user CHANGE created_by created_by INT DEFAULT NULL, CHANGE updated_by updated_by INT DEFAULT NULL, CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin, CHANGE verification_token verification_token VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE verified_at verified_at DATETIME DEFAULT \'NULL\', CHANGE is_active is_active TINYINT(1) DEFAULT \'NULL\', CHANGE created_at created_at DATETIME NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL');
    }
}
