<?php
declare(strict_types=1);

namespace Firegento\ContentProvisioning\Model\Query;

use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;

class HasDefaultPageConfiguration
{
    /**
     * @var PageRepositoryInterface
     */
    private $pageRepository;

    /**
     * @var GetPageEntryByPage
     */
    private $getPageEntryByPage;

    /**
     * @param PageRepositoryInterface $pageRepository
     * @param GetPageEntryByPage $getPageEntryByPage
     */
    public function __construct(
        PageRepositoryInterface $pageRepository,
        GetPageEntryByPage $getPageEntryByPage
    ) {
        $this->pageRepository = $pageRepository;

        $this->getPageEntryByPage = $getPageEntryByPage;
    }

    /**
     * @param int $entityId
     * @return bool
     */
    public function get(int $entityId): bool
    {
        try {
            $page = $this->pageRepository->getById($entityId);
            $entry = $this->getPageEntryByPage->execute($page);
            return $entry ? true : false;
        } catch (LocalizedException $noSuchEntityException) {
            return false;
        }
    }
}
