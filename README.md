# Cryptocurrency Portfolio System

## Table of contents

* [Running the application locally](#running-the-application-locally)
* [Documentation](#documentation)
  * [Application architecture](#application-architecture)
* [Core functionalities](#core-functionalities)
  * [Overview](#overview)
  * [Displaying coin price data](#displaying-coin-price-data)
  * [Creating multiple account-based portfolios](#creating-multiple-account-based-portfolios)

## Running the application locally
```shell
  $ npm run dev
  $ php artisan queue:work
  $ php artisan schedule:work
```

## Documentation

### Application architecture

* The application's server side runs on [**Laravel 11**](https://laravel.com/), which serves a 
[**Vue**](https://vuejs.org/) frontend using [**Inertia**](https://inertiajs.com/).  
* As for storing data, **PostgreSQL** is used along with **Redis**.
  * The application uses PostgreSQL to store general app & user data.
  * PostgreSQL is also used for managing the queue systems and its jobs.
  * Redis is used as a solution for caching.
* All coin data are fetched from [**CoinAPI.io**](https://www.coinapi.io/?_gl=1*1g124kb*_ga*MTIzMjgzMDMxMy4xNzM2MzIzODg1*_ga_EXCQW96F7R*MTczNjMyMzg4NC4xLjEuMTczNjMyMzg4OC4wLjAuMA..).

## Core functionalities

### Overview

The application offers basic functionalities to trade cryptocurrency:
* Display coin price data
* Create multiple account-based portfolios
* Add custom amounts of cryptocurrencies to portfolios by purchasing them
* Create price observation to coins
  * Set a target price for a coin, and get an email notification when the price is reached

### Displaying coin price data

As mentioned before, the application fetches all coin price data from [**CoinAPI.io**](https://www.coinapi.io/?_gl=1*1g124kb*_ga*MTIzMjgzMDMxMy4xNzM2MzIzODg1*_ga_EXCQW96F7R*MTczNjMyMzg4NC4xLjEuMTczNjMyMzg4OC4wLjAuMA..).  
Fetching starts by sending a GET request to **https://rest.coinapi.io/v1/assets**. As this sends back a huge JSON  
list, the fetching <em>does not</em> happen each time the page is loaded.  
Instead of fetching each time upon loading, there are two instances when all coin data are fetched:
> * On User login
> * On schedule
>  * A scheduled job is set to run **every 10 minutes**

Upon successfully fetching the coin data, all coin entries are updated in the PostgreSQL database   
or created in the case they did not previously exist.

Then, after loading the assets page the coin data are requested from the PostgreSQL database,  
then returned in a paginated form.

### Creating multiple account-based portfolios

Users may create **portfolios** in which they can keep their purchased coins. A user can have multiple portfolios.  
Upon deleting a portfolio, all coins held in it will also get sold (deleted) as well. 
