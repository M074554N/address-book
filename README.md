# Address Book

### Built with:
- PHP 7.4+
- Symfony 3.4
- Sqlite
- Twig + Bootstrap

### Go through the following steps
- Clone the repo and CD into it

`git clone https://github.com/M074554N/address-book.git && cd address-book`

- Install the composer packages

`composer install`

- Create the database schema

`php bin/console doctrine:schema:create`

- Run the server

`php bin/console server:run`

- View the address book at this URL: [http://127.0.0.1:8000/addressbook/](http://127.0.0.1:8000/addressbook/)

---

### Unit Test

- Run the following command to run the test

`./vendor/bin/simple-phpunit`
