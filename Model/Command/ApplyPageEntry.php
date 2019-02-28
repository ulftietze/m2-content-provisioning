<?php
declare(strict_types=1);

namespace Firegento\ContentProvisioning\Model\Command;

use Firegento\ContentProvisioning\Api\Data\PageEntryInterface;
use Firegento\ContentProvisioning\Model\Query\GetFirstPageByPageEntry;
use Magento\Cms\Api\Data\PageInterface;
use Magento\Cms\Api\Data\PageInterfaceFactory;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

class ApplyPageEntry
{
    /**
     * @var PageInterfaceFactory
     */
    private $pageFactory;

    /**
     * @var GetFirstPageByPageEntry
     */
    private $getFirstPageByPageEntry;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var PageRepositoryInterface
     */
    private $pageRepository;

    /**
     * @var NormalizeData
     */
    private $normalizeData;

    /**
     * @param PageInterfaceFactory $pageFactory
     * @param GetFirstPageByPageEntry $getFirstPageByPageEntry
     * @param PageRepositoryInterface $pageRepository
     * @param LoggerInterface $logger
     * @param NormalizeData $normalizeData
     */
    public function __construct(
        PageInterfaceFactory $pageFactory,
        GetFirstPageByPageEntry $getFirstPageByPageEntry,
        PageRepositoryInterface $pageRepository,
        LoggerInterface $logger,
        NormalizeData $normalizeData
    ) {
        $this->pageFactory = $pageFactory;
        $this->getFirstPageByPageEntry = $getFirstPageByPageEntry;
        $this->logger = $logger;
        $this->pageRepository = $pageRepository;
        $this->normalizeData = $normalizeData;
    }

    /**
     * @param PageEntryInterface $pageEntry
     * @throws LocalizedException
     */
    public function execute(PageEntryInterface $pageEntry): void
    {
        $page = $this->getFirstPageByPageEntry->execute($pageEntry);
        if ($page === null) {
            /** @var PageInterface $page */
            $page = $this->pageFactory->create([]);
        }
        $page->addData($this->normalizeData->execute($pageEntry->getData()));
        $this->pageRepository->save($page);
    }
}