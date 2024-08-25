## Система управления пользователями

### Развертывание

Конфиг докер окружения

указываем свой USER_NAME и ip в PHP_CLI_XDEBUG_HOST
```shell
cp .env.default .env
```
```shell
cp evo/.env.dist evo/.env
```

Устанавливаем зависимости composer
```shell
make install
```

(Возможно понадобится)
```shell
sudo adduser www-data
sudo usermod -a -G www-data www-data
```

Миграции
```shell
make migrate
```

Добавить ```user-manager.ru```
```shell
sudo nano /etc/hosts
```

Установить зависимости для react проекта и сбилдить его
```shell
cd user-manager-frontend && npm i && npm run build
```

### Учётная запись администратора по умолчанию
#### (только пользователь с правами администратора может выдавать права администратора)

Логин ```admin```

Пароль ```admin```