<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220514201557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE forma_pagamento (id INT AUTO_INCREMENT NOT NULL, descricao VARCHAR(200) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE foto_produto (id INT AUTO_INCREMENT NOT NULL, produto_id INT DEFAULT NULL, foto LONGBLOB NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_4E28712D105CFD56 (produto_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE grupo_permissoes (id INT AUTO_INCREMENT NOT NULL, descricao VARCHAR(200) NOT NULL, permissoes LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pedidos (id INT AUTO_INCREMENT NOT NULL, forma_pagamento_id INT NOT NULL, situacao_id INT NOT NULL, valor NUMERIC(10, 3) NOT NULL, removed INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_6716CCAA79AFB555 (forma_pagamento_id), INDEX IDX_6716CCAA95FC38A6 (situacao_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pedidos_itens (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pedidos_itens_pedidos (pedidos_itens_id INT NOT NULL, pedidos_id INT NOT NULL, INDEX IDX_DE46E4C74FAC5D28 (pedidos_itens_id), INDEX IDX_DE46E4C7213530F2 (pedidos_id), PRIMARY KEY(pedidos_itens_id, pedidos_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pedidos_itens_produtos (pedidos_itens_id INT NOT NULL, produtos_id INT NOT NULL, INDEX IDX_305F69F84FAC5D28 (pedidos_itens_id), INDEX IDX_305F69F865691519 (produtos_id), PRIMARY KEY(pedidos_itens_id, produtos_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produtos (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(200) NOT NULL, categoria INT NOT NULL, codigo_barra INT NOT NULL, valor NUMERIC(10, 3) NOT NULL, quantidade INT NOT NULL, descricao LONGTEXT NOT NULL, peso NUMERIC(10, 3) NOT NULL, removed INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE situacao (id INT AUTO_INCREMENT NOT NULL, descricao VARCHAR(200) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuarios (id INT AUTO_INCREMENT NOT NULL, grupo_id INT NOT NULL, nome VARCHAR(200) NOT NULL, sobrenome VARCHAR(200) NOT NULL, email VARCHAR(200) NOT NULL, password VARCHAR(200) NOT NULL, removed INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_EF687F29C833003 (grupo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE foto_produto ADD CONSTRAINT FK_4E28712D105CFD56 FOREIGN KEY (produto_id) REFERENCES produtos (id)');
        $this->addSql('ALTER TABLE pedidos ADD CONSTRAINT FK_6716CCAA79AFB555 FOREIGN KEY (forma_pagamento_id) REFERENCES forma_pagamento (id)');
        $this->addSql('ALTER TABLE pedidos ADD CONSTRAINT FK_6716CCAA95FC38A6 FOREIGN KEY (situacao_id) REFERENCES situacao (id)');
        $this->addSql('ALTER TABLE pedidos_itens_pedidos ADD CONSTRAINT FK_DE46E4C74FAC5D28 FOREIGN KEY (pedidos_itens_id) REFERENCES pedidos_itens (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pedidos_itens_pedidos ADD CONSTRAINT FK_DE46E4C7213530F2 FOREIGN KEY (pedidos_id) REFERENCES pedidos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pedidos_itens_produtos ADD CONSTRAINT FK_305F69F84FAC5D28 FOREIGN KEY (pedidos_itens_id) REFERENCES pedidos_itens (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pedidos_itens_produtos ADD CONSTRAINT FK_305F69F865691519 FOREIGN KEY (produtos_id) REFERENCES produtos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE usuarios ADD CONSTRAINT FK_EF687F29C833003 FOREIGN KEY (grupo_id) REFERENCES grupo_permissoes (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pedidos DROP FOREIGN KEY FK_6716CCAA79AFB555');
        $this->addSql('ALTER TABLE usuarios DROP FOREIGN KEY FK_EF687F29C833003');
        $this->addSql('ALTER TABLE pedidos_itens_pedidos DROP FOREIGN KEY FK_DE46E4C7213530F2');
        $this->addSql('ALTER TABLE pedidos_itens_pedidos DROP FOREIGN KEY FK_DE46E4C74FAC5D28');
        $this->addSql('ALTER TABLE pedidos_itens_produtos DROP FOREIGN KEY FK_305F69F84FAC5D28');
        $this->addSql('ALTER TABLE foto_produto DROP FOREIGN KEY FK_4E28712D105CFD56');
        $this->addSql('ALTER TABLE pedidos_itens_produtos DROP FOREIGN KEY FK_305F69F865691519');
        $this->addSql('ALTER TABLE pedidos DROP FOREIGN KEY FK_6716CCAA95FC38A6');
        $this->addSql('DROP TABLE forma_pagamento');
        $this->addSql('DROP TABLE foto_produto');
        $this->addSql('DROP TABLE grupo_permissoes');
        $this->addSql('DROP TABLE pedidos');
        $this->addSql('DROP TABLE pedidos_itens');
        $this->addSql('DROP TABLE pedidos_itens_pedidos');
        $this->addSql('DROP TABLE pedidos_itens_produtos');
        $this->addSql('DROP TABLE produtos');
        $this->addSql('DROP TABLE situacao');
        $this->addSql('DROP TABLE usuarios');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
