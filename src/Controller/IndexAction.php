<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\PageManager;
use Snowdog\DevTest\Model\User;
use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\WebsiteManager;

class IndexAction
{

    /**
     * @var WebsiteManager
     */
    private $websiteManager;

    /**
     * @var User
     */
    private $user;

    /**
     * @var User
     */
    private $pageManager;

    /**
     * @var Direction
     */
    private $direction = 'DESC';

    public function __construct(UserManager $userManager, WebsiteManager $websiteManager, PageManager $pageManager)
    {
        $this->websiteManager = $websiteManager;
        $this->pageManager = $pageManager;
        $this->userManager = $userManager;
        if (isset($_SESSION['login'])) {
            $this->user = $userManager->getByLogin($_SESSION['login']);
        }
        else {
            header('Location: /login');
        }
    }

    protected function getWebsites()
    {
        if ($this->user) {
            return $this->websiteManager->getAllByUser($this->user);
        }
        return [];
    }

    protected function getAllUserPages()
    {
        if ($this->user) {
            return $this->websiteManager->getAllByUser($this->user);
        }
        return [];
    }

    public function execute()
    {
        require __DIR__ . '/../view/index.phtml';
    }


    protected function getLeastVisitedPage()
    {
        $this->direction = 'ASC';
        return $this->getPages();
    }

    protected function getMostVisitedPage()
    {
        $this->direction = 'DESC';
        return $this->getPages();
    }

    private function getPages()
    {
        $websites = $this->getWebsites();

        if ($websites) {
            foreach ($websites as $website) {
                $websiteIds[] = $website->getWebsiteId();
            }

            $page = $this->pageManager->getPagesStats($websiteIds, $this->direction);
            if ($page) {
                $websiteId = $page->getWebsiteId();
                $websiteName = $this->websiteManager->getById($websiteId)->getHostname();
    
                return $websiteName . '/' . $page->getUrl() . ' - visited ' . $page->getVisitCounter() . ' times';
            }
            return '';
        }

        return '';
    }

    protected function getTotalUserPages()
    {
        return $this->userManager->getWebsitesByUser($this->user->getUserId());
    }
}
