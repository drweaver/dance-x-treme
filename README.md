# Dance-X-Treme

Code used for [Dance-X-Treme](www.dance-x-treme.co.uk) website.

Making heavy use of [Bootstrap](http://getbootstrap.com) and [Angular](https://angularjs.org).


## Validating JSON files

Install [JSONLint](https://www.npmjs.com/package/jsonlint):

`npm install jsonlint -g`

Validate:

`for i in data/*; do echo Validating $i; jsonlint -q $i; done`

## To Do

* Automated test for testing JSON validity
* Continuous Integration (at least for JSON file validation)
* Automated deployment (tagged releases only?)
