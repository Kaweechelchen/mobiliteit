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

## Examples

    https://mobiliteit.herokuapp.com/api/1/200405036
    https://mobiliteit.herokuapp.com/api/1/200405036/5

##Installation on your own heroku instance

1. Clone this repository using `git clone git@github.com:Kaweechelchen/mobiliteit.git`
* Get an [Heroku account](https://id.heroku.com/signup)
* install the [heroku toolbelt](https://toolbelt.heroku.com/)
* create a new heoku app using `heroku create`
* push the code `git push heroku master`

## License
Copyright (c) 2014 [Thierry Degeling](https://twitter.com/Kaweechelchen)
Licensed under the MIT license.

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
