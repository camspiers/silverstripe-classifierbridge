# SilverStripe Classifier Bridge

This library helps integrate classification services within SilverStripe sites.

## Installation (with composer)

	$ composer require camspiers/silverstripe-classifierbridge:dev-master

## Usage

### Integration via DataList and DataObject

1. Implement the Document interface on your DataObject

```php

use Camspiers\StatisticalClassifier\SilverStripe\Document;

class MyDataObject extends DataObject implements Document
{

	private static $db = array(
		'Content' => 'Text'
		'Spam' => 'Boolean'
	);
	
	
	public function getCategories()
	{
		return array($this->Spam ? 'spam' : 'ham');
	}

	public function getDocument()
	{
		return $this->Content;
	}

}
```

2. Use a DataList to retrieve the existing DataObjects and classify a new DataObject

```php
use Camspiers\StatisticalClassifier\Classifier\ComplementNaiveBayes;
use Camspiers\StatisticalClassifier\SilverStripe\DataSource;
use Camspiers\StatisticalClassifier\SilverStripe\Document;

// This DataObject could have been just populate via a form (e.g. $form->saveInto($myDataObject))
$dataObjectToClassify = new MyDataObject(
	array(
		'Content' => 'Some content'
	)
);

try {
	// A DataList is passed into a DataSource and then passed into the classifier
	$classifier = new ComplementNaiveBayes(new DataSource(MyDataObject::get()));
	if ($classifier->is('spam', $dataObjectToClassify->getDocument())) {
		// The document is spam
		// Perhaps set Spam = true on the DataObject and save it
	} else {
		// The document isn't spam
	}
} catch (Exception $e) {
	// Do something with the exception
}
```

### Integration via SQLQuery

Using SQLQuery can improve memory usage and execution time, because it bypasses the creation of DataObjects for each record

```php
use Camspiers\StatisticalClassifier\Classifier\ComplementNaiveBayes;
use Camspiers\StatisticalClassifier\DataSource\Grouped;
use Camspiers\StatisticalClassifier\SilverStripe\SQLQueryDataSource;
use Camspiers\StatisticalClassifier\SilverStripe\Document;

$spamQuery = new SQLQuery("Content, Spam", "MyDataObject", "Spam = 1");
$hamQuery = new SQLQuery("Content, Spam", "MyDataObject", "Spam = 0");

try {
	// Create the classifier by using a Grouped data source
	$classifier = new ComplementNaiveBayes(
		new Grouped(
			array(
				new SQLQueryDataSource("spam", $spamQuery, "Content"),
				new SQLQueryDataSource("ham", $hamQuery, "Content")
			)
		)
	);

	if ($classifier->is('spam', "Some content to classify")) {
		// The document is spam
		// Perhaps set Spam = true on the DataObject and save it
	} else {
		// The document isn't spam
	}
} catch (Exception $e) {
	// Do something with the exception
}
```

See [PHP Classifier](https://github.com/camspiers/statistical-classifier) for documentation around caching and more advanced topics.
