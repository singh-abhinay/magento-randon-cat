
Overview
--------

In the repository you will find Magento 2 module called Abhinay_RandomCat.

### Random Cat API

It's a simple JSON API with only one endpoint: https://randomcatapi.commerce.lingaro.dev/

It is protected by API key. To be authorized you must pass `api_key` GET parameter to the endpoint. The key that you may use is `5up3rc0nf1d3n714llp455w0rdf0rc47s`. Consider this key as a secret.

As a response for request to this API you will get simple JSON object with just one attribute called "url", eg. `{"url": "http://supercats.com/randomkitty123.jpg"}`.

The API is heavily loaded, so from time to time (approximately 25% of all requests) it will not respond with HTTP status 200.

Collection of cats photos used by the API is a little bit outdated, so from time to time (approximately 30% of successfull requests) it will return URL pointing to a 404 page.
