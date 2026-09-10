# One Device Login System — Railway Ready

PHP + MySQL one-device login and admin panel, prepared for Railway.

## Railway setup

1. Add a Railway MySQL service.
2. In the PHP web service, add `MYSQL_URL` with the MySQL service connection URL, for example:
   `mysql://root:PASSWORD@mysql.railway.internal:3306/railway`
3. Deploy this repository. Railway will use the included Dockerfile.
4. The application automatically creates its required tables on first request.

The app also accepts Railway's `MYSQLHOST`, `MYSQLPORT`, `MYSQLDATABASE`, `MYSQLUSER`, and `MYSQLPASSWORD` variables.

## Admin

Open `/admin.php` after deployment.

Default credentials in the included admin panel are:
- Username: `admin`
- Password: `admin1234`

Change these credentials in `admin.php` before using the site publicly.
