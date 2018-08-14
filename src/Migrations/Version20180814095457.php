<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180814095457 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tva (id INT AUTO_INCREMENT NOT NULL, percent INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA329E4EEDD');
        $this->addSql('DROP INDEX IDX_97A0ADA329E4EEDD ON ticket');
        $this->addSql('ALTER TABLE ticket CHANGE order_relation_id order_id INT NOT NULL');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA38D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA38D9F6D38 ON ticket (order_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE tva');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA38D9F6D38');
        $this->addSql('DROP INDEX IDX_97A0ADA38D9F6D38 ON ticket');
        $this->addSql('ALTER TABLE ticket CHANGE order_id order_relation_id INT NOT NULL');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA329E4EEDD FOREIGN KEY (order_relation_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA329E4EEDD ON ticket (order_relation_id)');
    }
}
