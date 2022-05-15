<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220515112546 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE forma_pagamento (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(200) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pedido_itens (id INT AUTO_INCREMENT NOT NULL, produto_id INT NOT NULL, pedido_id INT NOT NULL, valor NUMERIC(10, 3) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_8E468BE8105CFD56 (produto_id), INDEX IDX_8E468BE84854653A (pedido_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE situacao (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(200) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pedido_itens ADD CONSTRAINT FK_8E468BE8105CFD56 FOREIGN KEY (produto_id) REFERENCES produtos (id)');
        $this->addSql('ALTER TABLE pedido_itens ADD CONSTRAINT FK_8E468BE84854653A FOREIGN KEY (pedido_id) REFERENCES pedidos (id)');
        $this->addSql('ALTER TABLE pedidos ADD forma_pagamento_id INT DEFAULT NULL, ADD situacao_id INT NOT NULL, DROP forma_pagamento, DROP situacao');
        $this->addSql('ALTER TABLE pedidos ADD CONSTRAINT FK_6716CCAA79AFB555 FOREIGN KEY (forma_pagamento_id) REFERENCES forma_pagamento (id)');
        $this->addSql('ALTER TABLE pedidos ADD CONSTRAINT FK_6716CCAA95FC38A6 FOREIGN KEY (situacao_id) REFERENCES situacao (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6716CCAA79AFB555 ON pedidos (forma_pagamento_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6716CCAA95FC38A6 ON pedidos (situacao_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pedidos DROP FOREIGN KEY FK_6716CCAA79AFB555');
        $this->addSql('ALTER TABLE pedidos DROP FOREIGN KEY FK_6716CCAA95FC38A6');
        $this->addSql('DROP TABLE forma_pagamento');
        $this->addSql('DROP TABLE pedido_itens');
        $this->addSql('DROP TABLE situacao');
        $this->addSql('DROP INDEX UNIQ_6716CCAA79AFB555 ON pedidos');
        $this->addSql('DROP INDEX UNIQ_6716CCAA95FC38A6 ON pedidos');
        $this->addSql('ALTER TABLE pedidos ADD situacao INT NOT NULL, DROP forma_pagamento_id, CHANGE situacao_id forma_pagamento INT NOT NULL');
    }
}
