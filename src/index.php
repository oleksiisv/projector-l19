<?php
declare(strict_types=1);
require_once 'vendor/autoload.php';
const CREATE_TABLE = true;
const INSERT_BOOKS = true;
const BOOKS_NUMBER_TO_INSERT = 100;
/** Script entrypoint */
execute();
/**
 * @return void
 */
function execute()
{
    $start = microtime(true);

    $conn = pg_connect("host=db1 dbname=postgres user=postgres password=admin123")
    or die('Could not connect: ' . pg_last_error());

    if (CREATE_TABLE === true) {
        createTable($conn);
    }

    if (INSERT_BOOKS === true) {
        for ($i = 0; $i < BOOKS_NUMBER_TO_INSERT; $i++) {
            insertUserRow($conn, sampleUser());
        }
    }
    pg_close($conn);
    $delta = microtime(true) - $start;
    print_r($delta . "\n");
}

/**
 * @param $mysqli
 *
 * @return void
 */
function createTable($conn)
{
    $query = "CREATE TABLE IF NOT EXISTS books (
        id SERIAL,
        category_id  int not null,
        author character varying not null,
        title character varying not null,
        year int not null)";

    pg_query($conn, $query);
}

/**
 * @param $conn
 * @param $book
 *
 * @return void
 */
function insertUserRow($conn, $book)
{
    pg_query($conn, sprintf("
        INSERT INTO books (category_id, author, title, year) 
        VALUES ('%s', '%s', '%s', '%s')",
        $book['category_id'],
        pg_escape_string($conn, $book['author']),
        pg_escape_string($conn, $book['title']),
        $book['year']
    ));
}

/**
 * @return array
 * @throws Exception
 */
function sampleUser()
{
    /** @var Faker\Generator $faker */
    $faker = Faker\Factory::create();

    return [
        'category_id' => random_int(1, 3),
        'author' => sprintf('%s %s', $faker->firstName(), $faker->lastName()),
        'title' => $faker->title(),
        'year' => $faker->date('Y'),
    ];
}