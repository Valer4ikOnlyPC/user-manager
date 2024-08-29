## Система управления пользователями

### Развертывание

Конфиг докер окружения
<br>
указываем свой ```USER_NAME``` и ip в ```PHP_CLI_XDEBUG_HOST```
```shell
cp .env.default .env
```
```shell
cp evo/.env.dist evo/.env
```

Конфиг nginx
```shell
cp docker/etc/web/conf.d/default.conf.dist docker/etc/web/conf.d/default.conf
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
<br>

### Фронтенд
#### Установить зависимости для react проекта и сбилдить его.

Если в ```default.conf``` выбрана конфигурация для dev - ```npm start```, для prod - ```npm run build```.
<br>
Для dev - после, в фале ```default.conf```, в строке ```proxy_pass``` указываем порт на котором запустился проект


dev:
```shell
cd user-manager-frontend && npm i && npm start
```

prod:
```shell
cd user-manager-frontend && npm i && npm run build
```

### Учётная запись администратора по умолчанию
#### (только пользователь с правами администратора может выдавать права администратора)

Логин ```admin```
<br>
Пароль ```admin```