<?php

namespace Snowdog\DevTest\Migration;

use Snowdog\DevTest\Core\Database;
use Snowdog\DevTest\Model\PageManager;
use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\WebsiteManager;

class Version5
{
    /**
     * @var Database|\PDO
     */
    private $database;
    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var WebsiteManager
     */
    private $websiteManager;
    /**
     * @var PageManager
     */
    private $pageManager;

    public function __construct(
        Database $database
    ) {
        $this->database = $database;
    }

    public function __invoke()
    {
        //$this->createVarnishTable();
        $this->addVarnishColumnToWebsite();
    }

    private function createVarnishTable()
    {
        $createQuery = <<<SQL
        CREATE TABLE `varnish` (
          `varnish_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `user_id` int(11) NOT NULL, 
          `address` varchar(25) NOT NULL,
          PRIMARY KEY (`varnish_id`),
          UNIQUE KEY `address` (`address`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        SQL;

        $this->database->exec($createQuery);
    }

    private function addVarnishColumnToWebsite()
    {
        $query = <<<SQL
        ALTER TABLE `websites`
        ADD `varnish_id` INT(11)
        SQL;

        $this->database->exec($query);
    }
}
