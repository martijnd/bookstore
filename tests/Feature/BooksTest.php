<?php

use App\Book;
use App\User;

beforeEach(function () {
    $user = factory(User::class)->create();
    actingAs($user);
});

it('returns all books as a collection of resource objects', function () {
    $books = factory(Book::class, 3)->create();
    $this->get('/api/v1/books', [
        'accept' => 'application/vnd.api+json',
        'content-type' => 'application/vnd.api+json',
    ])->assertStatus(200)->assertJson([
        "data" => [
            [
                "id" => '1',
                "type" => "books",
                "attributes" => [
                    'title' => $books[0]->title,
                    'description' => $books[0]->description,
                    'publication_year' => $books[0]->publication_year,
                    'created_at' => $books[0]->created_at->toJSON(),
                    'updated_at' => $books[0]->updated_at->toJSON(),
                ]
            ],
            [
                "id" => '2',
                "type" => "books",
                "attributes" => [
                    'title' => $books[1]->title,
                    'description' => $books[1]->description,
                    'publication_year' => $books[1]->publication_year,
                    'created_at' => $books[1]->created_at->toJSON(),
                    'updated_at' => $books[1]->updated_at->toJSON(),
                ]
            ],
            [
                "id" => '3',
                "type" => "books",
                "attributes" => [
                    'title' => $books[2]->title,
                    'description' => $books[2]->description,
                    'publication_year' => $books[2]->publication_year,
                    'created_at' => $books[2]->created_at->toJSON(),
                    'updated_at' => $books[2]->updated_at->toJSON(),
                ]
            ],
        ]
    ]);
});


it('can create a book from a resource object', function () {
});

it('returns a book as a resource object', function () {
});

it('can update a book from a resource object', function () {
});

it('can delete a book through a delete request', function () {
});

it('validates that the type member is given when creating a book', function () {
});

it('validates that the type member has the value of books when creating a book', function () {
});

it('validates that the attributes member has been given when creating a book', function () {
});

it('validates that the attributes member is an object given when creating a book', function () {
});

it('validates that a name attribute is given when creating a book', function () {
});

it('validates that a name attribute is a string when creating a book', function () {
});

it('validates that an id member is given when updating a book', function () {
});

it('validates that an id member is a string when updating a book', function () {
});

it('validates that the type member is given when updating a book', function () {
});

it('validates that the type member has the value of books when updating a book', function () {
});

it('validates that the attributes member has been given when updating a book', function () {
});

it('validates that the attributes member is an object given when updating a book', function () {
});

it('validates that a name attribute is a string when updating a book', function () {
});

it('it can sort books by title through a sort query parameter', function () {
    $books = collect([
        'Building an API with Laravel',
        'Classes are our blueprints',
        'Adhering to the JSON:API Specification',
    ])->map(function ($title) {
        return factory(Book::class)->create([
            'title' => $title
        ]);
    });
    $this->get('/api/v1/books?sort=title', [
        'accept' => 'application/vnd.api+json',
        'content-type' => 'application/vnd.api+json',
    ])->assertStatus(200)->assertJson([
        "data" => [
            [
                "id" => '3',
                "type" => "books",
                "attributes" => [
                    'title' => 'Adhering to the JSON:API Specification',
                    'description' => $books[2]->description,
                    'publication_year' => $books[2]->publication_year,
                    'created_at' => $books[2]->created_at->toJSON(),
                    'updated_at' => $books[2]->updated_at->toJSON(),
                ]
            ],
            [
                "id" => '1',
                "type" => "books",
                "attributes" => [
                    'title' => 'Building an API with Laravel',
                    'description' => $books[0]->description,
                    'publication_year' => $books[0]->publication_year,
                    'created_at' => $books[0]->created_at->toJSON(),
                    'updated_at' => $books[0]->updated_at->toJSON(),
                ]
            ],
            [
                "id" => '2',
                "type" => "books",
                "attributes" => [
                    'title' => 'Classes are our blueprints',
                    'description' => $books[1]->description,
                    'publication_year' => $books[1]->publication_year,
                    'created_at' => $books[1]->created_at->toJSON(),
                    'updated_at' => $books[1]->updated_at->toJSON(),
                ]
            ],
        ]
    ]);
});

it('can sort books by name in descending order through a sort query parameter', function () {
    $books = collect([
        'Building an API with Laravel',
        'Classes are our blueprints',
        'Adhering to the JSON:API Specification',
    ])->map(function ($title) {
        return factory(Book::class)->create([
            'title' => $title
        ]);
    });
    $this->get('/api/v1/books?sort=-title', [
        'accept' => 'application/vnd.api+json',
        'content-type' => 'application/vnd.api+json',
    ])->assertStatus(200)->assertJson([
        "data" => [
            [
                "id" => '2',
                "type" => "books",
                "attributes" => [
                    'title' => 'Classes are our blueprints',
                    'description' => $books[1]->description,
                    'publication_year' => $books[1]->publication_year,
                    'created_at' => $books[1]->created_at->toJSON(),
                    'updated_at' => $books[1]->updated_at->toJSON(),
                ]
            ],
            [
                "id" => '1',
                "type" => "books",
                "attributes" => [
                    'title' => 'Building an API with Laravel',
                    'description' => $books[0]->description,
                    'publication_year' => $books[0]->publication_year,
                    'created_at' => $books[0]->created_at->toJSON(),
                    'updated_at' => $books[0]->updated_at->toJSON(),
                ]
            ],
            [
                "id" => '3',
                "type" => "books",
                "attributes" => [
                    'title' => 'Adhering to the JSON:API Specification',
                    'description' => $books[2]->description,
                    'publication_year' => $books[2]->publication_year,
                    'created_at' => $books[2]->created_at->toJSON(),
                    'updated_at' => $books[2]->updated_at->toJSON(),
                ]
            ],
        ]
    ]);
});

it('can sort books by multiple attributes through a sort query parameter', function () {

    $books = collect([
        'Building an API with Laravel',
        'Classes are our blueprints',
        'Adhering to the JSON:API Specification',
    ])->map(function ($title) {
        if ($title === 'Building an API with Laravel') {
            return factory(Book::class)->create([
                'title' => $title,
                'publication_year' => '2019',
            ]);
        }
        return factory(Book::class)->create([
            'title' => $title,
            'publication_year' => '2018',
        ]);
    });
    $this->get('/api/v1/books?sort=publication_year,title', [
        'accept' => 'application/vnd.api+json',
        'content-type' => 'application/vnd.api+json',
    ])->assertStatus(200)->assertJson([
        "data" => [
            [
                "id" => '3',
                "type" => "books",
                "attributes" => [
                    'title' => 'Adhering to the JSON:API Specification',
                    'description' => $books[2]->description,
                    'publication_year' => $books[2]->publication_year,
                    'created_at' => $books[2]->created_at->toJSON(),
                    'updated_at' => $books[2]->updated_at->toJSON(),
                ]
            ],
            [
                "id" => '2',
                "type" => "books",
                "attributes" => [
                    'title' => 'Classes are our blueprints',
                    'description' => $books[1]->description,
                    'publication_year' => $books[1]->publication_year,
                    'created_at' => $books[1]->created_at->toJSON(),
                    'updated_at' => $books[1]->updated_at->toJSON(),
                ]
            ],
            [
                "id" => '1',
                "type" => "books",
                "attributes" => [
                    'title' => 'Building an API with Laravel',
                    'description' => $books[0]->description,
                    'publication_year' => $books[0]->publication_year,
                    'created_at' => $books[0]->created_at->toJSON(),
                    'updated_at' => $books[0]->updated_at->toJSON(),
                ]
            ],
        ]
    ]);
});

it('can sort books by multiple attributes in descending order through a sort query parameter', function () {

    $books = collect([
        'Building an API with Laravel',
        'Classes are our blueprints',
        'Adhering to the JSON:API Specification',
    ])->map(function ($title) {
        if ($title === 'Building an API with Laravel') {
            return factory(Book::class)->create([
                'title' => $title,
                'publication_year' => '2019',
            ]);
        }
        return factory(Book::class)->create([
            'title' => $title,
            'publication_year' => '2018',
        ]);
    });
    $this->get('/api/v1/books?sort=-publication_year,title', [
        'accept' => 'application/vnd.api+json',
        'content-type' => 'application/vnd.api+json',
    ])->assertStatus(200)->assertJson([
        "data" => [
            [
                "id" => '1',
                "type" => "books",
                "attributes" => [
                    'title' => 'Building an API with Laravel',
                    'description' => $books[0]->description,
                    'publication_year' => $books[0]->publication_year,
                    'created_at' => $books[0]->created_at->toJSON(),
                    'updated_at' => $books[0]->updated_at->toJSON(),
                ]
            ],
            [
                "id" => '3',
                "type" => "books",
                "attributes" => [
                    'title' => 'Adhering to the JSON:API Specification',
                    'description' => $books[2]->description,
                    'publication_year' => $books[2]->publication_year,
                    'created_at' => $books[2]->created_at->toJSON(),
                    'updated_at' => $books[2]->updated_at->toJSON(),
                ]
            ],
            [
                "id" => '2',
                "type" => "books",
                "attributes" => [
                    'title' => 'Classes are our blueprints',
                    'description' => $books[1]->description,
                    'publication_year' => $books[1]->publication_year,
                    'created_at' => $books[1]->created_at->toJSON(),
                    'updated_at' => $books[1]->updated_at->toJSON(),
                ]
            ],
        ]
    ]);
});

it('can paginate books through a page query parameter', function () {
    $books = factory(Book::class, 10)->create();
    $this->get('/api/v1/books?page[size]=5&page[number]=1', [
        'accept' => 'application/vnd.api+json',
        'content-type' => 'application/vnd.api+json',
    ])->assertStatus(200)->assertJson([
        "data" => [
            [
                "id" => '1',
                "type" => "books",
                "attributes" => [
                    'title' => $books[0]->title,
                    'description' => $books[0]->description,
                    'publication_year' => $books[0]->publication_year,
                    'created_at' => $books[0]->created_at->toJSON(),
                    'updated_at' => $books[0]->updated_at->toJSON(),
                ]
            ],
            [
                "id" => '2',
                "type" => "books",
                "attributes" => [
                    'title' => $books[1]->title,
                    'description' => $books[1]->description,
                    'publication_year' => $books[1]->publication_year,
                    'created_at' => $books[1]->created_at->toJSON(),
                    'updated_at' => $books[1]->updated_at->toJSON(),
                ]
            ],
            [
                "id" => '3',
                "type" => "books",
                "attributes" => [
                    'title' => $books[2]->title,
                    'description' => $books[2]->description,
                    'publication_year' => $books[2]->publication_year,
                    'created_at' => $books[2]->created_at->toJSON(),
                    'updated_at' => $books[2]->updated_at->toJSON(),
                ]
            ],
            [
                "id" => '4',
                "type" => "books",
                "attributes" => [
                    'title' => $books[3]->title,
                    'description' => $books[3]->description,
                    'publication_year' => $books[3]->publication_year,
                    'created_at' => $books[3]->created_at->toJSON(),
                    'updated_at' => $books[3]->updated_at->toJSON(),
                ]
            ],
            [
                "id" => '5',
                "type" => "books",
                "attributes" => [
                    'title' => $books[4]->title,
                    'description' => $books[4]->description,
                    'publication_year' => $books[4]->publication_year,
                    'created_at' => $books[4]->created_at->toJSON(),
                    'updated_at' => $books[4]->updated_at->toJSON(),
                ]
            ],
        ],
        'links' => [
            'first' => route('books.index', ['page[size]' => 5, 'page[number]' => 1]),
            'last' => route('books.index', ['page[size]' => 5, 'page[number]' => 2]),
            'prev' => null,
            'next' => route('books.index', ['page[size]' => 5, 'page[number]' => 2]),
        ]
    ]);
});

it('can paginate books through a page query parameter and show different pages', function () {

    $books = factory(Book::class, 10)->create();
    $this->get('/api/v1/books?page[size]=5&page[number]=2', [
        'accept' => 'application/vnd.api+json',
        'content-type' => 'application/vnd.api+json',
    ])->assertStatus(200)->assertJson([
        "data" => [
            [
                "id" => '6',
                "type" => "books",
                "attributes" => [
                    'title' => $books[5]->title,
                    'description' => $books[5]->description,
                    'publication_year' => $books[5]->publication_year,
                    'created_at' => $books[5]->created_at->toJSON(),
                    'updated_at' => $books[5]->updated_at->toJSON(),
                ]
            ],
            [
                "id" => '7',
                "type" => "books",
                "attributes" => [
                    'title' => $books[6]->title,
                    'description' => $books[6]->description,
                    'publication_year' => $books[6]->publication_year,
                    'created_at' => $books[6]->created_at->toJSON(),
                    'updated_at' => $books[6]->updated_at->toJSON(),
                ]
            ],
            [
                "id" => '8',
                "type" => "books",
                "attributes" => [
                    'title' => $books[7]->title,
                    'description' => $books[7]->description,
                    'publication_year' => $books[7]->publication_year,
                    'created_at' => $books[7]->created_at->toJSON(),
                    'updated_at' => $books[7]->updated_at->toJSON(),
                ]
            ],
            [
                "id" => '9',
                "type" => "books",
                "attributes" => [
                    'title' => $books[8]->title,
                    'description' => $books[8]->description,
                    'publication_year' => $books[8]->publication_year,
                    'created_at' => $books[8]->created_at->toJSON(),
                    'updated_at' => $books[8]->updated_at->toJSON(),
                ]
            ],
            [
                "id" => '10',
                "type" => "books",
                "attributes" => [
                    'title' => $books[9]->title,
                    'description' => $books[9]->description,
                    'publication_year' => $books[9]->publication_year,
                    'created_at' => $books[9]->created_at->toJSON(),
                    'updated_at' => $books[9]->updated_at->toJSON(),
                ]
            ],
        ],
        'links' => [
            'first' => route('books.index', ['page[size]' => 5, 'page[number]' => 1]),
            'last' => route('books.index', ['page[size]' => 5, 'page[number]' => 2]),
            'prev' => route('books.index', ['page[size]' => 5, 'page[number]' => 1]),
            'next' => null,
        ]
    ]);
});
