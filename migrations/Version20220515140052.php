<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220515140052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pedidos DROP INDEX UNIQ_6716CCAA79AFB555, ADD INDEX IDX_6716CCAA79AFB555 (forma_pagamento_id)');
        $this->addSql('ALTER TABLE pedidos DROP INDEX UNIQ_6716CCAA95FC38A6, ADD INDEX IDX_6716CCAA95FC38A6 (situacao_id)');
        $this->addSql('ALTER TABLE pedidos CHANGE forma_pagamento_id forma_pagamento_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pedidos DROP INDEX IDX_6716CCAA79AFB555, ADD UNIQUE INDEX UNIQ_6716CCAA79AFB555 (forma_pagamento_id)');
        $this->addSql('ALTER TABLE pedidos DROP INDEX IDX_6716CCAA95FC38A6, ADD UNIQUE INDEX UNIQ_6716CCAA95FC38A6 (situacao_id)');
        $this->addSql('ALTER TABLE pedidos CHANGE forma_pagamento_id forma_pagamento_id INT DEFAULT NULL');
    }
}
