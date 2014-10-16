mobiliteit
==========

Gathering bus information from a specific station in Luxembourg is hard
This tool helps you to just get back the json that mobiliteit.lu should give you back

##Usage
- **[<code>GET</code> api/1/STATIONID/:amount](https://mobiliteit.herokuapp.com/api/1/200405036/5)**

every journey is going to include the following information:
- <code>timestamp</code> the unix timestamp of the departure
- <code>line</code> the line number of the bus
- <code>destination</code> the destination of the bus
- <code>delay</code> the delay that this departure has according to the schedule

## Example

    https://mobiliteit.herokuapp.com/api/1/200405036
    https://mobiliteit.herokuapp.com/api/1/200405036/5

##Installation on your own heroku instance

1. Clone this repository using `git clone git@github.com:Kaweechelchen/mobiliteit.git`
* Get an [Heroku account](https://id.heroku.com/signup)
* install the [heroku toolbelt](https://toolbelt.heroku.com/)
* create a new heoku app using `heroku create`
* push the code `git push heroku master`

## License
Copyright (c) 2014 [Thierry Degeling](https://github.com/Kaweechelchen)
Licensed under the MIT license.
