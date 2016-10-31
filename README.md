# TestTask1
Test task

**How to run project:**

1. Run composer to download all packages for project:
  ```php
  composer update
  ```

2. Create database:
  ```php
  php app/console doctrine:database:create
  ```

3. Prepare tables:
  ```php
  php app/console doctrine:schema:update --dump-sql
  ```

4. Clear cache:
  ```php
  php app/console cache:clear
  php app/console cache:clear --env=prod --no-debug
  ```

5. Install web resources:
  ```php
  php app/console assets:install web --symlink
  ```

6. Load fixtures data:
  ```php
  php app/console doctrine:fixtures:load
  ```
  or (Without purge data)
  ```php
  php app/console doctrine:fixtures:load --append
  ```

7. Run project:
  ```php
  php app/console server:run localhost:9090
  ```
