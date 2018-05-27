<?php

namespace Nasa\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Generate neo table
 */
class Version20170827154604 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('create table neo (id int auto_increment not null, created_at timestamp not null default current_timestamp, approach_at date not null, name varchar(100) not null, reference int, speed double, is_hazardous boolean, primary key(id)) default character set utf8 collate utf8_unicode_ci engine = innodb');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('drop table neo');
    }
}
