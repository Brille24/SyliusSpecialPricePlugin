<?php
declare(strict_types=1);

namespace Tests\Brille24\SyliusSpecialPricePlugin\Behat\Context\Ui\Admin;


use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Behat\Service\Resolver\CurrentPageResolverInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Tests\Application\SyliusSpecialPricePlugin\Entity\ProductVariant;
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
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(
        ProductVariantCreatePage $createPage,
        ProductVariantUpdatePage $updatePage,
        CurrentPageResolverInterface $currentPageResolver,
        EntityManagerInterface $em
    ) {
        $this->createPage = $createPage;
        $this->updatePage = $updatePage;
        $this->currentPageResolver = $currentPageResolver;
        $this->em = $em;
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
        /** @var ProductVariantCreatePage|ProductVariantUpdatePage $currentPage */
        $currentPage = $this->currentPageResolver->getCurrentPageWithForm([$this->createPage, $this->updatePage]);

        Assert::same($currentPage->getSpecialPricesValidationMessage(), 'The start date must be smaller than the end date.');
    }

    /**
     * @Then I should be notified that the dates cannot overlap
     */
    public function iShouldBeNotifiedThatTheDatesCannotOverlap()
    {
        /** @var ProductVariantCreatePage|ProductVariantUpdatePage $currentPage */
        $currentPage = $this->currentPageResolver->getCurrentPageWithForm([$this->createPage, $this->updatePage]);

        Assert::same($currentPage->getSpecialPricesValidationMessage(), 'The date ranges should not overlap.');
    }

    /**
     * @Then I should be notified that the price field cannot be empty
     */
    public function iShouldBeNotifiedThatThePriceFieldCannotBeEmpty()
    {
        /** @var ProductVariantCreatePage|ProductVariantUpdatePage $currentPage */
        $currentPage = $this->currentPageResolver->getCurrentPageWithForm([$this->createPage, $this->updatePage]);

        Assert::same($currentPage->getSpecialPricesValidationMessage(), 'This value should not be null.');
    }

    /**
     * @Then the :variant variant should have :count special price(s)
     */
    public function theVariantShouldHaveSpecialPrices(ProductVariant $variant, int $count)
    {
        // The product variant is loaded from cache, therefore we have to refresh it manually.
        $this->em->refresh($variant);

        Assert::count($variant->getChannelSpecialPricings(), $count);
    }

    /**
     * @Then the :variant variant should have :count special price(s) for channel :channel
     */
    public function theVariantShouldHaveSpecialPriceForChannel(ProductVariant $variant, int $count, ChannelInterface $channel)
    {
        // The product variant is loaded from cache, therefore we have to refresh it manually.
        $this->em->refresh($variant);

        Assert::count($variant->getChannelSpecialPricingsForChannel($channel), $count);
    }
}
