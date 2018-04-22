# symfony4-starter
a symfony 4 app with fos-user , phpmailer , easy-admin and vich-uploader configuration, 


# installation

1. git clone https://github.com/NacerDev/symfony4-starter.git
2. cd symfony4-starter
3. composer install

# configuration
update the .env file with your parameters

1. DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name

2. MAILER_USER_ADRESS=your-adress-mail

3. MAILER_USER_PASSWORD=your-password

4. MAILER_HOST=your-smtp-host 

5. MAILER_PORT=your-smtp-host-port 

# database
1. $ php bin/console doctrine:database:create
2. $ php bin/console make:migration
3. $ php bin/console doctrine:migrations:migrate
