<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191012002045 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ext_log_entries (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(8) NOT NULL, logged_at DATETIME NOT NULL, object_id VARCHAR(64) DEFAULT NULL, object_class VARCHAR(255) NOT NULL, version INT NOT NULL, data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', username VARCHAR(255) DEFAULT NULL, INDEX log_class_lookup_idx (object_class), INDEX log_date_lookup_idx (logged_at), INDEX log_user_lookup_idx (username), INDEX log_version_lookup_idx (object_id, object_class, version), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('ALTER TABLE user ADD created_by INT DEFAULT NULL, ADD updated_by INT DEFAULT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, CHANGE roles roles JSON NOT NULL, CHANGE verification_token verification_token VARCHAR(255) DEFAULT NULL, CHANGE verified_at verified_at DATETIME DEFAULT NULL, CHANGE is_active is_active TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649DE12AB56 FOREIGN KEY (created_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64916FE72E1 FOREIGN KEY (updated_by) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649DE12AB56 ON user (created_by)');
        $this->addSql('CREATE INDEX IDX_8D93D64916FE72E1 ON user (updated_by)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE ext_log_entries');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649DE12AB56');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64916FE72E1');
        $this->addSql('DROP INDEX IDX_8D93D649DE12AB56 ON user');
        $this->addSql('DROP INDEX IDX_8D93D64916FE72E1 ON user');
        $this->addSql('ALTER TABLE user DROP created_by, DROP updated_by, DROP created_at, DROP updated_at, CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin, CHANGE verification_token verification_token VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE verified_at verified_at DATETIME DEFAULT \'NULL\', CHANGE is_active is_active TINYINT(1) DEFAULT \'NULL\'');
    }
}
