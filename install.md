# Installation

cp .env.dist .env
make up
make exec ARGS="composer install"
php bin/console telegram:webhook:set

# Helpers

> Move to composer scripts

php bin/console doctrine:schema:validate
php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:migrations:migrate prev

vendor/bin/php-cs-fixer fix --dry-run --diff
vendor/bin/php-cs-fixer fix --dry-run --diff --verbose > 1.txt
vendor/bin/php-cs-fixer fix

vendor/bin/deptrac analyse --config-file=deptrac-layers.yaml
vendor/bin/deptrac analyse --config-file=deptrac-modules.yaml

php bin/console telegram:webhook:unset && php bin/console telegram:webhook:set

# Telegram

-4521537757

# Questions

- Какой подход лучше использовать для сущностей\агрегатов - анемичная модель или богатая модель?


- Как лучше генерировать идентификаторы сущностей - UUID или INT autoincrement? (в случае с UUID мы генерируем ID на
  уровне приложения и не нужно ходить в базу за ними, плюс можно будет безболезненно сделать асинхронные команды)


- Если мы создаем DTO для того чтобы где то отобразить данные сущности, то в DTO должны были ValueObject или скаляры? (в
  случае, если у сущности все поля - это ValueObject)
- имеет ли смысл писать тесты на классы, которые находятся на уровне доменной области, если там нет бизнес-логики, а
  только сущности, фабрики, репозитории?
- нужно ли создавать ValueObject для поля, которое должно хранить дату? (например: создать класс, который будет
  наследовать DateTimeImmutable, это позволит иметь подготовленные методы для работы с датами и плюс добавить метод,
  который будет возвращать строковое представление даты, например, в определенном формате)
  Дата рождения, если created_at, update_at - то можно

/startTrip Test
dispatch->StartTripCommand

/joinTrip Evgeniy
ask->GetActiveTripByChatId
dispatch->StartTripCommand

```php
$this->addSql('CREATE UNIQUE INDEX unique_active_trip_per_chat ON tet_trips (chat_id) WHERE is_active = TRUE');

$this->addSql('DROP INDEX unique_active_trip_per_chat ON tet_trips');
```
