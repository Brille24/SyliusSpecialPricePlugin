# Sylius Special Price Plugin
[![Build Status](https://travis-ci.org/Brille24/SyliusSpecialPricePlugin.svg?branch=master)](https://travis-ci.org/Brille24/SyliusSpecialPricePlugin)

>[!warning]
> THIS PLUGIN IS ABANDONED. Please use the catalog promotion functionality (in 1.13 and later) for this.

This plugin provides a configuration for prices that are just active within a certain date range. So if you want to change the price for a product between the 1st May and the 1st of April, this plugin is for you.

### Installation
1. Require the plugin
    ```bash
    composer require brille24/sylius-special-price-plugin
    ```
    
2. Register the plugin in your ```bundles.php```
    ```php
    return [
        ...
        Brille24\SyliusSpecialPricePlugin\Brille24SyliusSpecialPricePlugin::class => ['all' => true],
    ];
    ```
    
3. Import plugin config
    ```yaml
    imports:
        ...
        - { resource: "@Brille24SyliusSpecialPricePlugin/Resources/config/config.yml" }
    ```

4. Add migrations
	1. Copy migrations from `tests/Application/migration` to your desired spot for migrations.

5. Override ProductVariant entity
    1. Write new class which will use ProductVariantSpecialPricableTrait and implement ProductVariantSpecialPricableInterface
    2. Override the models class in config
        ```yaml
        sylius_product:
            resources:
                product_variant:
                    classes:
                        model: Brille24\SyliusSpecialPricePlugin\Entity\ProductVariant
        ```

6. Add mapping and validation
    1. Mapping
        ```xml
        <!-- ProductVariant.orm.xml -->
 
        <doctrine-mapping xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping">
            <mapped-superclass name="Your\Name\Space\ProductVariant" table="sylius_product_variant">
                <one-to-many field="channelSpecialPricings"
                             target-entity="Brille24\SyliusSpecialPricePlugin\Entity\ChannelSpecialPricingInterface"
                             mapped-by="productVariant" orphan-removal="true">
                    <cascade>
                        <cascade-all/>
                    </cascade>
                </one-to-many>
            </mapped-superclass>
        </doctrine-mapping>
        ```
    2. Validation
        ```xml
        <!-- ProductVariant.xml -->
    
        <constraint-mapping xmlns="http://symfony.com/schema/dic/constraint-mapping">
            <class name="Your\Name\Space\ProductVariant">
                <constraint
                        name="Brille24\SyliusSpecialPricePlugin\Validator\ProductVariantChannelSpecialPriceDateOverlapConstraint">
                    <option name="groups">sylius</option>
                </constraint>
            </class>
        </constraint-mapping>
        ```

6. Override ```SyliusAdminBundle/ProductVariant/Tab/_details.html.twig```
    
    Add this:
    ```twig
    <div class="ui segment">
        {% include 'Brille24SyliusSpecialPricePlugin::_specialPrice.html.twig' with {
            'form': form.channelSpecialPricings
        } %}
    </div>
    ```

7. Update the database schema
    ```bash
    bin/console doctrine:schema:update --force
    ```

### Running the test server
From the plugin root directory, run the following commands:

```bash
(cd tests/Application && yarn install)
(cd tests/Application && yarn build)
(cd tests/Application && bin/console assets:install --symlink)

(cd tests/Application && bin/console doctrine:database:create)
(cd tests/Application && bin/console doctrine:schema:update --force)
(cd tests/Application && bin/console sylius:fixtures:load)

(cd tests/Application && bin/console server:start)
```

### Testing
In order to run the phpspec tests you need to run the command `vendor/bin/phpspec run`
In order to run the phpunit tests you need to run the command `vendor/bin/phpunit`
In order to run the behat tests you need to run the command `vendor/bin/behat`
