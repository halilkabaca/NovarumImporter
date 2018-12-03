# NovarumImporter
JSON importer plugin for Wordpress. It helps you call any REST Api endpoint and import JSON response to wordpress. It supports custom post types as well.

## Handling Api Requests ##
Go to Settings->Novarum Importer and open "Request Options" tab. From this tab you can define how the api should be called and what parameters should be passed. Note that, this plugin uses PHP curl extension so it has to be enabled on the server side. Most of the hosting providers enable this feature by default.

* URL: This field is to define the url that will be called.

* Request Type: This is to choose what verb should be used when calling the url. Default is GET

* Fields: If the request type is POST or PUT, you can define the fields that can be passed as request body. If you are making a GET call you would need to add fields to the url instead.

* Authentication Type: Some api endpoints require you to pass Username and Password as Basic Authentication parameters on the header of the request. If that's the case, you can choose Basic Authentication and define the Username and Password. Apart from that, it's common for REST apis to require tokens on request body, on this case you would need to define it on Fields field.

* Headers: Some REST Api endpoints require specific header parameters, ie content type. If that's the case, you can define the header values on this text field. If you need to define more than one header key, please add one item per line. Ie:

Content-Type: application/x-www-form-urlencoded; charset=utf-8

Host: www.example.com

User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0

## Setting up the Importer ##

If the request is correct and you get a valid JSON as response, you can now set up the parameters on how to import the data to wordpress.

* Array key: This parameter is the key the plugin will iterate through to import all the items under this array. Ie, if your JSON response is as:

{
  "response":
  {
   "data": [ {"title": "item 1", "description": "Description 1"},
             {"title": "item 2", "description": "Description 2"},
           ]
  }
}

Then you can set Response.data as the array key

* Title key: Which key should be taken as the post's title. This key should be under the Array key so according to above example it would be "title"

* Description key: Which key should be taken as the post's content. This key should be under the Array key so according to above example it would be "description"

* Date key:  Which key should be taken as the post's date. This key should be under the Array key and it can be left blank. If it's left blank it takes the current date as the post's date.

## Notes ##
This plugin is under heavy development. If you have any suggestions, comments, or issues please send it to team@novarumsoftware.com or open a request on Github.

## TODO ##
* Allow Meta tags and taxonomies to be imported as well
* Make the api calls in the background
* Realtime status display for import process
* Allow api calls to be scheduled
* Special settings for paginations (either with page value or with offset,limit combination)

