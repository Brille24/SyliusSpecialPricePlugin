[![Build Status](https://travis-ci.org/Brille24/SyliusSpecialPricePlugin.svg?branch=master)](https://travis-ci.org/Brille24/SyliusSpecialPricePlugin)

### Installation ###
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

4. Override ProductVariant entity
    1. Write new class which will use ProductVariantSpecialPricableTrait and implement ProductVariantSpecialPricableInterface
    2. Override the models class in config
        ```yaml
        sylius_product:
            resources:
                product_variant:
                    classes:
                        model: Brille24\SyliusSpecialPricePlugin\Entity\ProductVariant
        ```

5. Update the database schema
    ```bash
    bin/console doctrine:schema:update --force
    ```

### Opening Sylius from the plugin

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
