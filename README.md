<p align="center">
    UMB Akademik Surat Elektronik Apps, created with [Filament](https://github.com/filamentphp/filament) and Custom Plugins [Superduper-filament-starter-kit by Rio Dewanto P](https://github.com/riodwanto/superduper-filament-starter-kit)
</p>

#### Features

-   Craete Surat with Multiple Accepting by Role
-   Generate Surat with pdf format
-   QR Code Generator in Surat
-   Validate surat with QR Code scan
-   Etc..


#### Latest update
###### Version: v1.0

#### How to Install

Create project with this clone this repository:

```bash
git clone https://github.com/restualqubro/filament-umb-akademik.git
or
git clone https://restualqubro@github.com/restualqubro/filament-umb-akademik.git
```

Setup your env:

```bash
cd filament-umb-akademik
cp .env.example .env
```

Run migration & seeder:

```bash
php artisan migrate
php artisan db:seed
```

<p align="center">or</p>

```bash
php artisan migrate:fresh --seed
```

Generate key:

```bash
php artisan key:generate
```

Run :

```bash
npm run dev
OR
npm run build
```

```bash
php artisan serve
```

Now you can access with `/admin` path, using:

```bash
email: superadmin@starter-kit.com
password: superadmin
```

*It's recommend to run below command as suggested in [Filament Documentation](https://filamentphp.com/docs/3.x/panels/installation#improving-filament-panel-performance) for improving panel perfomance.*

```bash
php artisan icons:cache
```
