### Opening Sylius with your plugin

From the plugin root directory, run the following commands:

```bash
$ (cd tests/Application && yarn install)
$ (cd tests/Application && yarn build)
$ (cd tests/Application && bin/console assets:install --symlink)

$ (cd tests/Application && bin/console doctrine:database:create)
$ (cd tests/Application && bin/console doctrine:schema:update --force)
$ (cd tests/Application && bin/console sylius:fixtures:load)

$ (cd tests/Application && bin/console server:start)

```
