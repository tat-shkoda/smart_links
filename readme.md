## Умные ссылки

Сервис умных ссылок с редиректом по условиям



### Установка
```
docker compose run --rm composer install --working-dir=admin
docker compose run --rm composer install --working-dir=redirect
```



### Запуск тестов
```
docker compose run --rm admin php vendor/bin/phpunit --coverage-text
docker compose run --rm redirect php vendor/bin/phpunit --coverage-text
```



### Бизнес процессы

#### 1. Создание ссылки

```mermaid
flowchart LR
    Send(Пользователь заполненил данные) --> Valid{Валидация пройдена?}
    Valid -- Нет --> Error[Показать ошибку]
    Valid -- Да --> Save[Сохранить ссылку]
    Save --> End([Ссылка создана])
```

#### 2. Переход по ссылке

```mermaid
flowchart LR
    Open([Пользователь открыл ссылку]) --> Rules[Получить правила]
    Rules --> Match{Условие
    выполнилось?}
    Match -- Да --> DefaultTarget[Адрес из правила]
    Match -- Нет --> RuleTarget[Адрес по умолчанию]
    DefaultTarget --> End([Редирект])
    RuleTarget --> End
```



### функциональные процессы

Пользователь -> Создать ссылку
Пользователь -> Открыть ссылку

Сервис редиректа -> получить ссылку и правила по коду ссылке



### копонентная схема

```mermaid
flowchart TB
    User[Пользователь]

    Admin[Сервис управления ссылками]
    Redirect[Сервис редиректа]

    Redis[(Redis)]
    Memcached[(Memcached)]

    User --> Admin
    User --> Redirect

    Redirect --> Memcached
    Admin --> Redis

    Redirect --> Admin

```



### информационная схема

```mermaid
flowchart TD
    User[Пользователь]

    Admin[Админка]
    Redirect[Редирект сервис]

    User -- Создать ссылку --> Admin
    Redirect -- Получить правила для ссылки --> Admin
    User -- Открыть ссылку --> Redirect
```



### описание интеграций

![Описание интеграций](integrations.png)
