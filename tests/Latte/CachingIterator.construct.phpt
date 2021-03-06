<?php

/**
 * Test: Latte\Runtime\CachingIterator constructor.
 */

use Latte\Runtime\CachingIterator,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test(function() { // ==> array
	$arr = array('Nette', 'Framework');
	$tmp = array();
	foreach (new CachingIterator($arr) as $k => $v) $tmp[] = "$k => $v";
	Assert::same( array(
		'0 => Nette',
		'1 => Framework',
	), $tmp );
});


test(function() { // ==> stdClass
	$arr = (object) array('Nette', 'Framework');
	$tmp = array();
	foreach (new CachingIterator($arr) as $k => $v) $tmp[] = "$k => $v";
	Assert::same( array(
		'0 => Nette',
		'1 => Framework',
	), $tmp );
});


test(function() { // ==> IteratorAggregate
	$arr = new ArrayObject(array('Nette', 'Framework'));
	$tmp = array();
	foreach (new CachingIterator($arr) as $k => $v) $tmp[] = "$k => $v";
	Assert::same( array(
		'0 => Nette',
		'1 => Framework',
	), $tmp );
});

test(function() { // ==> Iterator
	$arr = new ArrayObject(array('Nette', 'Framework'));
	$tmp = array();
	foreach (new CachingIterator($arr->getIterator()) as $k => $v) $tmp[] = "$k => $v";
	Assert::same( array(
		'0 => Nette',
		'1 => Framework',
	), $tmp );
});


test(function() { // ==> SimpleXMLElement
	$arr = new SimpleXMLElement('<feed><item>Nette</item><item>Framework</item></feed>');
	$tmp = array();
	foreach (new CachingIterator($arr) as $k => $v) $tmp[] = "$k => $v";
	Assert::same( array(
		'item => Nette',
		'item => Framework',
	), $tmp );
});


test(function() { // ==> object
	Assert::exception(function() {
		$arr = dir('.');
		foreach (new CachingIterator($arr) as $k => $v);
	}, 'InvalidArgumentException', NULL);
});


class RecursiveIteratorAggregate implements IteratorAggregate {
	public function getIterator() {
		return new ArrayObject(array('Nette', 'Framework'));
	}
}


test(function() { // ==> recursive IteratorAggregate
	$arr = new RecursiveIteratorAggregate;
	$tmp = array();
	foreach (new CachingIterator($arr) as $k => $v) $tmp[] = "$k => $v";
	Assert::same( array(
		'0 => Nette',
		'1 => Framework',
	), $tmp );
});
