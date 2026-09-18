# Fake Data Generator for RetailCRM

Консольная команда на Symfony, которая наполняет RetailCRM демо-данными:
генерирует клиентов с адресами и заказы с товарными позициями через официальный API.

## Возможности

- Генерация клиентов: ФИО, email, телефон, дата рождения, адрес.
- Генерация заказов: привязка к существующему клиенту, товарные позиции, статус, тип заказа, страна.
- Прогресс-бар в консоли для отслеживания процесса.
- Логирование ошибок API в `var/log/dev.log`.
- Гибкая настройка через переменные окружения.

## Требования

- PHP 8.1 или выше
- Composer
- Аккаунт RetailCRM с доступом к API (URL и API-ключ) и настроенными справочниками (типы заказов, статусы, магазины)

## Установка

```bash
git clone git@github.com:your-username/fake-generator.git
cd fake-generator
composer install
```

## Настройка

Создай локальный файл окружения:

```bash
cp .env .env.local
```

Заполни в `.env.local`:

```dotenv
RETAILCRM_URL=https://your-account.retailcrm.ru
RETAILCRM_API_KEY=your_api_key
RETAILCRM_SITE=your_site_code
RETAILCRM_ORDER_TYPE=main
RETAILCRM_ORDER_COUNTRY=RU
```

| Переменная | Описание |
|------------|----------|
| `RETAILCRM_URL` | Адрес аккаунта RetailCRM |
| `RETAILCRM_API_KEY` | API-ключ (Настройки → Интеграции → API-ключи) |
| `RETAILCRM_SITE` | Код магазина (Настройки → Магазины) |
| `RETAILCRM_ORDER_TYPE` | Код типа заказа из справочника |
| `RETAILCRM_ORDER_COUNTRY` | ISO 3166-1 alpha-2 (например, `RU`) |

## Использование

Запусти команду, указав количество генераций клиентов и заказов:

```bash
php bin/console app:generate-fake-data --quantity-generations=100
```

Опция `--quantity-generations` необязательна. По умолчанию генерируется **10** клиентов и столько же заказов (по одному на каждого успешно созданного клиента).

### Пример вывода

```
Создание клиентов:
 100/100 [============================] 100% 1 min, 49 secs/1 min, 49 secs 14.0 MiB

Создание заказов:
 100/100 [============================] 100% 2 min, 5 secs/2 min, 5 secs 16.0 MiB
```

## Как это работает

1. `CustomerGenerator` создаёт объект `CustomerData` (DTO) со случайными данными через Faker.
2. `RetailCrmClient::createCustomer` отправляет клиента в RetailCRM и возвращает его внутренний ID.
3. `OrderGenerator` формирует массив данных заказа на основе DTO клиента и сгенерированных товарных позиций.
4. `RetailCrmClient::createOrder` отправляет заказ с привязкой к клиенту по ID.
5. Все ошибки API логируются через Monolog в `var/log/dev.log`.

## Структура проекта

```
src/
├── Command/
│   └── GenerateFakeDataCommand.php   # Оркестратор: клиенты → заказы
├── Dto/
│   ├── AddressData.php               # DTO адреса
│   └── CustomerData.php              # DTO клиента
└── Service/
    ├── RetailCrmClient.php           # Обёртка над официальным API-клиентом
    └── FakeData/
        ├── CustomerGenerator.php     # Генерация клиента
        └── OrderGenerator.php        # Генерация заказа и позиций
config/
└── services.yaml                     # Регистрация Faker и RetailCRM-клиента
```

## Логирование

Ошибки API, ответы с ошибками, неожиданные исключения пишутся в `var/log/dev.log`.

Посмотреть последние ошибки:

```bash
tail -50 var/log/dev.log
```

## Ограничения

1. Команда не удаляет уже созданные данные — при повторных запусках создаются новые записи.
2. Создание клиентов и заказов выполняется последовательно, без параллельных запросов.
3. Позиции товаров генерируются без привязки к реальному каталогу — только название, количество и цена.

## Лицензия

MIT
```