# Beer search program

This program will generate the best path to 
fly in order to aquire the most different types of beer in your area.

### Installing

First you need to clone this project to your local storage.

```
git clone git@github.com:MindaugasElvikis/beer.git
```

Then install composer dependencies

```
composer install
```

After that, you should update your database schema.

```
php bin/console doctrine:schema:update --force
```

Next, you should import all beers, breweries and their locations.
By default it will read from local files, add ```remote``` argument to fetch from remote server.

```
php bin/console brewery:import
```

In order to start generating best path to aquire beer you should run the following command:
None: ```lat``` and ```long``` options are your coordinates.
```
php bin/console beer:search --lat=51.742503 --long=19.432956
```

## Authors

* [**Mindaugas Elvikis**](https://github.com/MindaugasElvikis)

See also the list of [contributors](https://github.com/MindaugasElvikis/beer/graphs/contributors) who participated in this project.
