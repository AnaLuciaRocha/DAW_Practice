<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210205014818 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE rememberme_token');
        $this->addSql('ALTER TABLE orders CHANGE user_id user_id INT DEFAULT NULL, CHANGE product_id product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE products CHANGE cat_id cat_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users CHANGE name name VARCHAR(255) DEFAULT \'NULL\', CHANGE email email VARCHAR(180) NOT NULL, CHANGE password_digest password_digest VARCHAR(255) NOT NULL, CHANGE remember_digest remember_digest VARCHAR(255) DEFAULT \'NULL\', CHANGE activation_digest activation_digest VARCHAR(255) DEFAULT \'NULL\', CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('DROP INDEX email ON users');
        $this->addSql('CREATE UNIQUE INDEX index_users_on_email ON users (email)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rememberme_token (series CHAR(88) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, value VARCHAR(88) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, lastUsed DATETIME NOT NULL, class VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, username VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(series)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE orders CHANGE user_id user_id INT NOT NULL, CHANGE product_id product_id INT NOT NULL');
        $this->addSql('ALTER TABLE products CHANGE cat_id cat_id INT NOT NULL');
        $this->addSql('ALTER TABLE users CHANGE name name VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE email email VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE roles roles LONGTEXT CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci` COMMENT \'(DC2Type:json)\', CHANGE password_digest password_digest VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE remember_digest remember_digest VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE activation_digest activation_digest VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`');
        $this->addSql('DROP INDEX index_users_on_email ON users');
        $this->addSql('CREATE UNIQUE INDEX email ON users (email)');
    }
}
