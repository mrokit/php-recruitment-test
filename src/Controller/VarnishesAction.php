<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\Varnish;
use Snowdog\DevTest\Model\VarnishManager;
use Snowdog\DevTest\Model\WebsiteManager;

class VarnishesAction
{
    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var VarnishManager
     */
    private $varnishManager;

    /** @var \Snowdog\DevTest\Model\User $user */
    private $user;
    /**
     * @var WebsiteManager
     */
    private $websiteManager;

    public function __construct(UserManager $userManager, VarnishManager $varnishManager, WebsiteManager $websiteManager)
    {
        $this->userManager = $userManager;
        $this->varnishManager = $varnishManager;
        if (isset($_SESSION['login'])) {
            $this->user = $this->userManager->getByLogin($_SESSION['login']);
        }
        else {
            header('Location: /');
        }
        $this->websiteManager = $websiteManager;
    }

    public function getVarnishes()
    {
        if ($this->user) {
            return $this->varnishManager->getAllByUser($this->user);
        }
        return [];
    }

    public function getWebsites()
    {
        if ($this->user) {
            return $this->websiteManager->getAllByUser($this->user);
        }
        return [];
    }

    public function getAssignedWebsiteIds(Varnish $varnish)
    {
        $websites = $this->varnishManager->getWebsites($varnish);
        $ids = [];
        foreach ($websites as $website) {
            $ids[] = $website->getWebsiteId();
        }
        return $ids;
    }

    public function execute()
    {

        include __DIR__ . '/../view/varnish.phtml';
    }

    public function associateWebsite($data = null)
    {
        var_dump($data);die;
    }

    public function removeVarnishFromWebsite($data)
    {
        $websiteId = 22;
        $this->varnishManager->unlink($websiteId);
    }
}
