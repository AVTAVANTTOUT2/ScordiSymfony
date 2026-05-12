<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260415141444 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attachment (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) NOT NULL, original_name VARCHAR(255) DEFAULT NULL, message_id INT NOT NULL, INDEX IDX_795FD9BB537A1329 (message_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(120) NOT NULL, position INT NOT NULL, server_id INT NOT NULL, INDEX IDX_64C19C11844E6B7 (server_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE channel (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(80) NOT NULL, type VARCHAR(20) NOT NULL, position INT NOT NULL, server_id INT NOT NULL, category_id INT DEFAULT NULL, INDEX IDX_A2F98E471844E6B7 (server_id), INDEX IDX_A2F98E4712469DE2 (category_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE direct_message (id INT AUTO_INCREMENT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, thread_id INT NOT NULL, author_id INT NOT NULL, INDEX IDX_1416AF93E2904019 (thread_id), INDEX IDX_1416AF93F675F31B (author_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE direct_message_thread (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, user_a_id INT NOT NULL, user_b_id INT NOT NULL, INDEX IDX_CEA2C670415F1F91 (user_a_id), INDEX IDX_CEA2C67053EAB07F (user_b_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE invitation (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(32) NOT NULL, expires_at DATETIME DEFAULT NULL, server_id INT NOT NULL, UNIQUE INDEX UNIQ_F11D61A277153098 (code), INDEX IDX_F11D61A21844E6B7 (server_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, author_id INT NOT NULL, channel_id INT NOT NULL, INDEX IDX_B6BD307FF675F31B (author_id), INDEX IDX_B6BD307F72F5A1AA (channel_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE server (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(80) NOT NULL, icon_path VARCHAR(255) DEFAULT NULL, owner_id INT NOT NULL, INDEX IDX_5A6DD5F67E3C61F9 (owner_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE server_member (id INT AUTO_INCREMENT NOT NULL, role VARCHAR(20) NOT NULL, server_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_998C3BE1844E6B7 (server_id), INDEX IDX_998C3BEA76ED395 (user_id), UNIQUE INDEX UNIQ_998C3BE1844E6B7A76ED395 (server_id, user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(20) NOT NULL, avatar_path VARCHAR(255) DEFAULT NULL, bio VARCHAR(255) DEFAULT NULL, presence_status VARCHAR(20) DEFAULT \'offline\' NOT NULL, last_seen_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE attachment ADD CONSTRAINT FK_795FD9BB537A1329 FOREIGN KEY (message_id) REFERENCES message (id)');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C11844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)');
        $this->addSql('ALTER TABLE channel ADD CONSTRAINT FK_A2F98E471844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)');
        $this->addSql('ALTER TABLE channel ADD CONSTRAINT FK_A2F98E4712469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE direct_message ADD CONSTRAINT FK_1416AF93E2904019 FOREIGN KEY (thread_id) REFERENCES direct_message_thread (id)');
        $this->addSql('ALTER TABLE direct_message ADD CONSTRAINT FK_1416AF93F675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE direct_message_thread ADD CONSTRAINT FK_CEA2C670415F1F91 FOREIGN KEY (user_a_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE direct_message_thread ADD CONSTRAINT FK_CEA2C67053EAB07F FOREIGN KEY (user_b_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE invitation ADD CONSTRAINT FK_F11D61A21844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F72F5A1AA FOREIGN KEY (channel_id) REFERENCES channel (id)');
        $this->addSql('ALTER TABLE server ADD CONSTRAINT FK_5A6DD5F67E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE server_member ADD CONSTRAINT FK_998C3BE1844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)');
        $this->addSql('ALTER TABLE server_member ADD CONSTRAINT FK_998C3BEA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attachment DROP FOREIGN KEY FK_795FD9BB537A1329');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C11844E6B7');
        $this->addSql('ALTER TABLE channel DROP FOREIGN KEY FK_A2F98E471844E6B7');
        $this->addSql('ALTER TABLE channel DROP FOREIGN KEY FK_A2F98E4712469DE2');
        $this->addSql('ALTER TABLE direct_message DROP FOREIGN KEY FK_1416AF93E2904019');
        $this->addSql('ALTER TABLE direct_message DROP FOREIGN KEY FK_1416AF93F675F31B');
        $this->addSql('ALTER TABLE direct_message_thread DROP FOREIGN KEY FK_CEA2C670415F1F91');
        $this->addSql('ALTER TABLE direct_message_thread DROP FOREIGN KEY FK_CEA2C67053EAB07F');
        $this->addSql('ALTER TABLE invitation DROP FOREIGN KEY FK_F11D61A21844E6B7');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF675F31B');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F72F5A1AA');
        $this->addSql('ALTER TABLE server DROP FOREIGN KEY FK_5A6DD5F67E3C61F9');
        $this->addSql('ALTER TABLE server_member DROP FOREIGN KEY FK_998C3BE1844E6B7');
        $this->addSql('ALTER TABLE server_member DROP FOREIGN KEY FK_998C3BEA76ED395');
        $this->addSql('DROP TABLE attachment');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE channel');
        $this->addSql('DROP TABLE direct_message');
        $this->addSql('DROP TABLE direct_message_thread');
        $this->addSql('DROP TABLE invitation');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE server');
        $this->addSql('DROP TABLE server_member');
        $this->addSql('DROP TABLE `user`');
    }
}
