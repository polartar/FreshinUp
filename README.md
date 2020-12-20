# FoodFleet
Foodfleet is a client project to utilize the following modules:
* [FreshBUS Forms](https://github.com/FreshinUp/fresh-bus-forms)

## Release Pipeline
### Staging Server https://foodfleet.freshinup.com
Automatically deployed as `master` branch of this repository is updated.

<br/>

## Contributing
For process instructions on introducing changes please read the [Platform Readme](https://github.com/FreshinUp/fresh-platform/blob/master/README.md) and [Wiki](https://github.com/FreshinUp/fresh-platform/wiki)

### Server Setup

#### Queue setup
On https://forge.laravel.com go on `Site Details` and under `Queue` tab create a queue worker with `Run Worker As Daemon (Laravel 4.2+)` option selected.
On envoyer go on `Deploy Hooks  »  Activate New Release` and under `After This Action` section create a new Hook with the following script:
```
cd {{project}}
php artisan queue:restart
```
with `forge` user and `On Servers` option selected

#### Command schedule setup
On https://forge.laravel.com go on `Server` and under `Scheduler` tab create a new scheduler job with following options:
Command: `php /home/forge/default/artisan schedule:run`
User: `forge`
Frequency: `Every minute`

#### Square setup
- create an account on `https://developer.squareup.com`
- create a test application
- set your env variables related to square
```
SQUARE_ACCESS_TOKEN=<your-app-access-token>
SQUARE_APP_ID=<your-app-id>
SQUARE_APP_SECRET=<your-app-secret>
SQUARE_ENVIRONMENT=<sandbox|production>
```
IF you are on staging:
- go back to `https://developer.squareup.com` dashboard page and create a test user WITHOUT any permission on the app
- click on the `Launch` button right after the test account
- the test account will remain active for a limited amount of time. After that it will be necessary re-launch the account from the developer dashboard
- To connect a new account you need to hit
`https://connect.squareup(sandbox).com?client_id=$clientId`
(if in dev)
It is important that you're sign in to your square dashboard when try the authorization
- the redirect url should be set to `$baseUrl/admin/contractor/check`. $baseUrl being your https server (use ngrok if needed) ie https://foodfleet.freshinup.com/admin/contractor/check, http://c5e152320a0b.ngrok.io/admin/contractor/check but not localhost

Login->fleet member detail page -> connect square -> callback -> redirect back


#### Mapbox setup
- Create a developer account on https://mapbox.com
- retrieve access token from https://account.mapbox.com/access-tokens/

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
$ nvm install
$ composer install
$ php artisan foodfleet:install --dev
$ php artisan passport-client:update-env
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
- add `ssl: true` to homestead.yaml
- add 
```
buckets:
    - name: footfleet
      policy: public
    - name: footfleet-bus
      policy: public
    - name: footfleet-tmp
      policy: public
```
- `$ vagrant up --provision`
- add homestead site to your hosts file:
`192.168.10.10   homestead`
- the local configuration for projects on homestead has to be (it's different from the docker configuration):
```
# Filesystem for FoodFleet
FS_FF_KEY=homestead
FS_FF_SECRET=secretkey
FS_FF_ENDPOINT=http://homestead:9600
FS_FF_BUCKET=footfleet
FS_FF_REGION=us-east-1
FS_FF_ROOT=
FS_FF_BUCKET_ENDPOINT=false
FS_FF_PATH_STYLE_ENDPOINT=true

# TMP Storage
FS_TMP_KEY=homestead
FS_TMP_SECRET=secretkey
FS_TMP_ENDPOINT=http://homestead:9600
FS_TMP_BUCKET=footfleet-tmp
FS_TMP_REGION=us-east-1
FS_TMP_ROOT=
FS_TMP_BUCKET_ENDPOINT=false
FS_TMP_PATH_STYLE_ENDPOINT=true

# Filesystem for BUS media
FS_BUS_KEY=homestead
FS_BUS_SECRET=secretkey
FS_BUS_ENDPOINT=http://homestead:9600
FS_BUS_BUCKET=footfleet-bus
FS_BUS_REGION=us-east-1
FS_BUS_ROOT=
FS_BUS_BUCKET_ENDPOINT=false
FS_BUS_PATH_STYLE_ENDPOINT=true
```

- take out from your homestead machine the `ca.homestead.homestead.crt` file in the `/etc/nginx/ssl/` folder
- add it to certificate exception on chrome advanced settings on `authorities` tab

- create an account on `https://developer.squareup.com`
- create a test application and set it as sandbox
- set your callback url in the sandbox app under Oauth based on your base url (eg: `https://foodfleet.test/admin/contractor/check`)
- set your env variables related to square
```
SQ_TOKEN=<your-app-token>
SQ_APP_ID=<your-app-id>
SQ_APP_SECRET=<your-app-secret>
SQ_DOMAIN=https://connect.squareupsandbox.com
```
- go back to `https://developer.squareup.com` dashboard page and create a test user WITHOUT any permission on the app
- click on the `Launch` button right after the test account
- the test account will remain active for a limited amount of time. After that it will be necessary re-launch the account from the developer dashboard
