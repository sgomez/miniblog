<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Doctrine\DBAL\Schema\Table;

$console = new Application('Mini Blog', '1.0');

$console
    ->register('create-schema')
    ->setDefinition(array())
    ->setDescription('Executes the SQL needed to generate the database schema')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        $schema = $app['db']->getSchemaManager();

        if (!$schema->tablesExist('entry')) {
        	$entry = new Table('entry');

        	$entry->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
        	$entry->setPrimaryKey(array('id'));
        	$entry->addColumn('title', 'string', array('length' => 255));
        	$entry->addColumn('content', 'string', array('length' => 5000));
        	$entry->addColumn('created_at', 'datetime');

        	$schema->createTable($entry);
        	$output->writeln("<info>Done.</info>");
        }
        else {
        	$output->writeln("<info>Schema already exits.</info>");
        }
    })
;

return $console;
