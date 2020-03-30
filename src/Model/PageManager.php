<?php

namespace Snowdog\DevTest\Model;

use Snowdog\DevTest\Core\Database;

class PageManager
{

    /**
     * @var Database|\PDO
     */
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getAllByWebsite(Website $website)
    {
        $websiteId = $website->getWebsiteId();
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM pages WHERE website_id = :website');
        $query->bindParam(':website', $websiteId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_CLASS, Page::class);
    }

    public function create(Website $website, $url)
    {
        $websiteId = $website->getWebsiteId();
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('INSERT INTO pages (url, website_id) VALUES (:url, :website)');
        $statement->bindParam(':url', $url, \PDO::PARAM_STR);
        $statement->bindParam(':website', $websiteId, \PDO::PARAM_INT);
        $statement->execute();
        return $this->database->lastInsertId();
    }

    public function setPageTime($pageId, $time)
    {
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('UPDATE pages SET last_visit_time = :time WHERE page_id = :pageId');
        $statement->bindParam(':time', $time, \PDO::PARAM_STR);
        $statement->bindParam(':pageId', $pageId, \PDO::PARAM_INT);
        $statement->execute();
        return $this->database->lastInsertId();
    }

    public function getTime($pageId)
    {
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('SELECT last_visit_time WHERE page_id = :pageId');
        $statement->bindParam(':pageId', $pageId, \PDO::PARAM_INT);
        $statement->execute();
        return $this->database->lastInsertId();
    }

    public function setCounter($pageId)
    {
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('UPDATE pages SET visit_counter = visit_counter + 1 WHERE page_id = :pageId');
        $statement->bindParam(':pageId', $pageId, \PDO::PARAM_INT);
        $statement->execute();
        return $this->database->lastInsertId();
    }

    public function getPagesStats($websiteIds, $direction)
    {
        $websiteIds = implode(',', array_map('intval', $websiteIds));
        /** @var \PDOStatement $query */
        $query = $this->database->prepare("SELECT url, website_id, visit_counter FROM pages WHERE website_id IN ($websiteIds) AND visit_counter > 0 ORDER BY visit_counter $direction LIMIT 1");
        $query->execute();
        return $query->fetchObject(Page::class);
    }
}