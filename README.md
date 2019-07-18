# FoodFleet
FactRight is a client project to utilize the following modules:
* [FreshBUS Forms](https://github.com/FreshinUp/fresh-bus-forms)

## Release Pipeline
### Staging Server https://foodfleet.freshinup.com
Automatically deployed as `master` branch of this repository is updated.

<br/>

## Contributing
For process instructions on introducing changes please read the [Platform Readme](https://github.com/FreshinUp/fresh-platform/blob/master/README.md) and [Wiki](https://github.com/FreshinUp/fresh-platform/wiki)

### Development Setup
#### Requirements
* Git (https://git-scm.com/downloads)
* PHP 7.1+ (https://www.php.net/downloads.php)
* Composer (https://getcomposer.org/download/)
* Yarn (https://yarnpkg.com/en/docs/getting-started)
* Node 8 (https://nodejs.org/en/download/)
> The following assumes your local SSH keys are registered with your GitHub account. However, feel free to use HTTPS cloning.

```bash
$ git clone git@github.com:FreshinUp/foodfleet.git
$ cd foodfleet
$ composer install
```
* Copy `.env.example` to `.env`
* Update `.env` with your database settings (`DB_*`) and application URL (`APP_URL`).
```bash
$ php artisan foodfleet:install
$ php artisan key:generate
-- Only enter the next command if you want to seed the database with demo data
$ php artisan fresh-bus:seed --quickstart
-- Compile Javascript libraries and auto-recompile on file changes
$ yarn watch-poll
```

From this point on you'll most likely be executing this command
```bash
$ php artisan fresh-bus:update --dev
$ yarn watch-poll
```

## Media Library Configuration

### Local Development (Homestead)
- add `minio: true` to homestead.yaml
- add 
```
buckets:
    - name: cms
      policy: public
    - name: tmp
      policy: public
```
- `$ vagrant up --provision`
- add homestead site to your hosts file:
`192.168.10.10   homestead`
- the local configuration for projects on homestead has to be (it's different from the docker configuration):
```
# TMP Storage
FS_TMP_KEY=homestead
FS_TMP_SECRET=secretkey
FS_TMP_ENDPOINT=http://homestead:9600
FS_TMP_BUCKET=tmp
FS_TMP_REGION=us-east-1

# Filesystem for Content Management media
FS_CMS_KEY=homestead
FS_CMS_SECRET=secretkey
FS_CMS_ENDPOINT=http://homestead:9600
FS_CMS_BUCKET=cms
FS_CMS_REGION=us-east-1
FS_CMS_ROOT=

# Filesystem for BUS media
FS_CMS_KEY=homestead
FS_CMS_SECRET=secretkey
FS_CMS_ENDPOINT=http://homestead:9600
FS_CMS_BUCKET=bus
FS_CMS_REGION=us-east-1
FS_CMS_ROOT=
```
