<?php
declare(strict_types=1);

namespace Tests\Brille24\SyliusSpecialPricePlugin\Behat\Context\Ui\Admin;


use Behat\Behat\Context\Context;
use Brille24\SyliusSpecialPricePlugin\Entity\ProductVariantInterface;
use Sylius\Behat\NotificationType;
use Sylius\Behat\Service\NotificationCheckerInterface;
use Sylius\Behat\Service\Resolver\CurrentPageResolverInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Tests\Brille24\SyliusSpecialPricePlugin\Behat\Page\Admin\ProductVariantCreatePage;
use Tests\Brille24\SyliusSpecialPricePlugin\Behat\Page\Admin\ProductVariantUpdatePage;
use Webmozart\Assert\Assert;

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
     * @When I remove a special price for channel :channel
     */
    public function iRemoveASpecialPriceForChannel(ChannelInterface $channel)
    {
        /** @var ProductVariantCreatePage|ProductVariantUpdatePage $currentPage */
        $currentPage = $this->currentPageResolver->getCurrentPageWithForm([$this->createPage, $this->updatePage]);

        $currentPage->removeSpecialPriceForChannel($channel);
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
    public function iSetTheStartDateTo(\DateTime $date, ChannelInterface $channel)
    {
        /** @var ProductVariantCreatePage|ProductVariantUpdatePage $currentPage */
        $currentPage = $this->currentPageResolver->getCurrentPageWithForm([$this->createPage, $this->updatePage]);

        $currentPage->setStartDate($date, $channel);
    }

    /**
     * @When I set the end date to :date for channel :channel
     */
    public function iSetTheEndDateTo(\DateTime $date, ChannelInterface $channel)
    {
        /** @var ProductVariantCreatePage|ProductVariantUpdatePage $currentPage */
        $currentPage = $this->currentPageResolver->getCurrentPageWithForm([$this->createPage, $this->updatePage]);

        $currentPage->setEndDate($date, $channel);
    }

    /**
     * @When /^I set the price to ("[^"]+") for (channel "([^"]+)")$/
     */
    public function iSetThePriceTo($price, ChannelInterface $channel)
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
        $this->notificationChecker->checkNotification('brille24.product_variant.channel_special_pricing.start_before_end', NotificationType::failure());
    }

    /**
     * @Then I should be notified that the dates cannot overlap
     */
    public function iShouldBeNotifiedThatTheDatesCannotOverlap()
    {
        $this->notificationChecker->checkNotification('brille24.product_variant.channel_special_pricing.dates_overlap', NotificationType::failure());
    }

    /**
     * @Then the :variant variant should have :count special price(s)
     */
    public function theVariantShouldHaveSpecialPrices(ProductVariantInterface $variant, int $count)
    {
        Assert::count($variant->getChannelSpecialPricings(), $count);
    }

    /**
     * @Then the :variant variant should have :count special price(s) for channel :channel
     */
    public function theVariantShouldHaveSpecialPriceForChannel(ProductVariantInterface $variant, int $count, ChannelInterface $channel)
    {
        Assert::count($variant->getChannelSpecialPricingsForChannel($channel), $count);
    }
}
