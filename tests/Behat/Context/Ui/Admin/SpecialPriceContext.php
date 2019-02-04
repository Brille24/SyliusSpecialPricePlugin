<?php
declare(strict_types=1);

namespace Tests\Brille24\SyliusSpecialPricePlugin\Behat\Context\Ui\Admin;


use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Sylius\Behat\NotificationType;
use Sylius\Behat\Service\NotificationCheckerInterface;
use Sylius\Behat\Service\Resolver\CurrentPageResolverInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Tests\Brille24\SyliusSpecialPricePlugin\Behat\Page\Admin\ProductVariantCreatePage;
use Tests\Brille24\SyliusSpecialPricePlugin\Behat\Page\Admin\ProductVariantUpdatePage;

class SpecialPriceContext implements Context
{
    /**
     * @var CurrentPageResolverInterface
     */
    private $currentPageResolver;
    /**
     * @var ProductVariantCreatePage
     */
    private $createPage;
    /**
     * @var ProductVariantUpdatePage
     */
    private $updatePage;
    /**
     * @var NotificationCheckerInterface
     */
    private $notificationChecker;

    public function __construct(
        ProductVariantCreatePage $createPage,
        ProductVariantUpdatePage $updatePage,
        CurrentPageResolverInterface $currentPageResolver,
        NotificationCheckerInterface $notificationChecker
    ) {
        $this->createPage = $createPage;
        $this->updatePage = $updatePage;
        $this->currentPageResolver = $currentPageResolver;
        $this->notificationChecker = $notificationChecker;
    }

    /**
     * @When I add a special price for channel :channel
     */
    public function iAddASpecialPriceForChannel(ChannelInterface $channel)
    {
        /** @var ProductVariantCreatePage|ProductVariantUpdatePage $currentPage */
        $currentPage = $this->currentPageResolver->getCurrentPageWithForm([$this->createPage, $this->updatePage]);

        $currentPage->addSpecialPriceForChannel($channel);
    }

    /**
     * @Then I should be notified that the price field cannot be empty
     */
    public function iShouldBeNotifiedThatThePriceFieldCannotBeEmpty()
    {
        $this->notificationChecker->checkNotification('This value should not be null.', NotificationType::failure());
    }

    /**
     * @When I set the start date to :date for channel :channel
     */
    public function iSetTheStartDateTo(\DateTime $dateTime, ChannelInterface $channel)
    {
        /** @var ProductVariantCreatePage|ProductVariantUpdatePage $currentPage */
        $currentPage = $this->currentPageResolver->getCurrentPageWithForm([$this->createPage, $this->updatePage]);

        $currentPage->setStartDate($dateTime, $channel);
    }

    /**
     * @When I set the end date to :arg1 for channel :channel
     */
    public function iSetTheEndDateTo($arg1)
    {
        throw new PendingException();
    }

    /**
     * @When I set the price to :price for channel :channel
     */
    public function iSetThePriceTo(int $price, ChannelInterface $channel)
    {
        /** @var ProductVariantCreatePage|ProductVariantUpdatePage $currentPage */
        $currentPage = $this->currentPageResolver->getCurrentPageWithForm([$this->createPage, $this->updatePage]);

        $currentPage->setPrice($price, $channel);
    }

    /**
     * @Then I should be notified that the start date must be smaller than the end date
     */
    public function iShouldBeNotifiedThatTheStartDateMustBeSmallerThanTheEndDate()
    {
        throw new PendingException();
    }

    /**
     * @Then I should be notified that the dates cannot overlap
     */
    public function iShouldBeNotifiedThatTheDatesCannotOverlap()
    {
        throw new PendingException();
    }
}
