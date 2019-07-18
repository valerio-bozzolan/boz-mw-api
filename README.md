# boz-mw

This is another MediaWiki API handler in PHP.

## Download

	git clone https://github.com/valerio-bozzolan/boz-mw

## Command line tools

See [the tools](./tools/README.md).

## API framework showcase

Here some usage examples.

### Basic API query

```php
<?php
require 'boz-mw/autoload.php';

// load it.wiki
$wiki = \wm\WikipediaIt::instance();

$response =
	$wiki->fetch( [
		'action' => 'query',
		'prop'   => 'info',
		'titles' => [
			'Pagina principale'
		]
	] );

print_r( $response );
```

### API query with continuation


```php
$wiki = \wm\WikipediaIt::instance();

$queries =
	$wiki->createQuery( [
		'action'  => 'query',
		'list'    => 'categorymembers',
		'cmtitle' => 'Categoria:Software con licenza GNU GPL',
	] );

foreach( $queries as $query ) {
	print_r( $query );
}
```

### Edit API query

```php
$wiki = \wm\WikipediaIt::instance();

$user     = '';
$password = '';
$wiki->login( $user, $password );

$wiki->edit( [
	'title'   => 'Special:Nothing',
	'text'    => 'My wikitext',
	'summary' => 'My edit summary',
] );
```

### Wikidata SPARQL query

What if you want to list all the [cats from Wikidata](https://query.wikidata.org/#%23Cats%0ASELECT%20%3Fitem%20%3FitemLabel%20%0AWHERE%20%0A%7B%0A%20%20%3Fitem%20wdt%3AP31%20wd%3AQ146.%0A%20%20SERVICE%20wikibase%3Alabel%20%7B%20bd%3AserviceParam%20wikibase%3Alanguage%20%22%5BAUTO_LANGUAGE%5D%2Cen%22.%20%7D%0A%7D)?

```php
// you should know how to build a SPARQL query
$query  = 'SELECT ?item ?itemLabel WHERE {';
$query .= ' ?item wdt:P31 wd:Q146 ';
$query .= ' SERVICE wikibase:label { bd:serviceParam wikibase:language "en". } ';
$query .= '}';

// query Wikidata and decode the response
$rows = \wm\Wikidata::querySPARQL( $query );

// for each cat
foreach( $rows as $row ) {

	// example: 'http://www.wikidata.org/entity/Q5317221'
	$url = $row->item->value;

	// example: 'Q5317221'
	$id = basename( $url );

	// example: 'Dusty the Klepto Kitty'
	$itemLabel = $row->itemLabel->value;

	echo "Found cat ID: $id. Name: $itemLabel \n";
}
```

### Upload API query

Uploading a file requires to respect the [RFC1341](https://tools.ietf.org/html/rfc1341) about an HTTP multipart request.

Well, we made it easy:

```php
$photo_url = 'http://.../libre-image.jpg';
$wiki->upload( [
	'comment'  => 'upload file about...',
	'text'     => 'bla bla [[bla]]',
	'filename' => 'Libre image.jpg',
	\network\ContentDisposition::createFromNameURLType( 'file', $photo_url, 'image/jpg' ),
] );
```

See the [`ContentDisposition`](include/class-network\ContentDisposition.php) class for some other constructors.

Well, I have not the time to document also the Wikibase part, but hey, we support it quite well.

## Known usage
* [MediaWikiOrphanizerBot](https://github.com/valerio-bozzolan/MediaWikiOrphanizerBot)
* [ItalianWikipediaDeletionBot](https://github.com/valerio-bozzolan/ItalianWikipediaDeletionBot)
* [wiki-users-leaflet](https://github.com/valerio-bozzolan/wiki-users-leaflet/) hosted at [WMF Labs](https://tools.wmflabs.org/it-wiki-users-leaflet/)
