<?php

namespace Snowdog\DevTest\Model;

use Snowdog\DevTest\Core\Database;
use Snowdog\DevTest\Model\WebsiteManager;
use Snowdog\DevTest\Model\UserManager;

class VarnishManager
{

    /**
     * @var Database|\PDO
     */
    private $database;

    public function __construct(Database $database, WebsiteManager $websiteManager, UserManager $userManager)
    {
        $this->database = $database;
    }

    public function getAllByUser(User $user)
    {
        $userId = $user->getUserId();
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('SELECT varnish_id, address FROM varnish WHERE user_id = :userId');
        $statement->bindParam(':userId', $userId, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_CLASS, Varnish::class);
    }

    public function getWebsites(Varnish $varnish)
    {
        $varnishId = $varnish->getVarnishId();
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('SELECT * FROM websites WHERE varnish_id = :varnishId');
        $statement->bindParam(':varnishId', $varnishId, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_CLASS, Website::class);
    }

    public function getByWebsite(Website $website)
    {
        $websiteId = $website->getWebsiteId();
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('SELECT varnish_id FROM websites WHERE website_id = :websiteId');
        $statement->bindParam(':websiteId', $websiteId, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch(\PDO::FETCH_CLASS, Website::class);
    }

    public function create(User $user, $ip)
    {
        $userId = $user->getUserId();
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('INSERT INTO varnish (user_id, address) VALUES (:userId, :address)');
        $statement->bindParam(':userId', $userId, \PDO::PARAM_INT);
        $statement->bindParam(':address', $ip, \PDO::PARAM_STR);
        $statement->execute();
        return $this->database->lastInsertId();
    }

    public function link($varnish, $website)
    {
        $varnishId = $varnish->getVarnishId();
        $websiteId = $website->getWebsiteId();
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('UPDATE websites SET varnish_id = :varnishId WHERE website_id = :websiteId');
        $statement->bindParam(':varnishId', $varnishId, \PDO::PARAM_INT);
        $statement->bindParam(':websiteId', $websiteId, \PDO::PARAM_INT);
        $statement->execute();
        return $this->database->lastInsertId();
    }

    public function unlink($website)
    {
        $websiteId = $website->getWebsiteId();
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('UPDATE websites SET varnish_id = null WHERE website_id = :websiteId');
        $statement->bindParam(':websiteId', $websiteId, \PDO::PARAM_INT);
        $statement->execute();
        return $this->database->lastInsertId();
    }

}