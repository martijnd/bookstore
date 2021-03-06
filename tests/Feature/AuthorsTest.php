<?php

use App\Author;
use App\User;

beforeEach(function () {
    $user = factory(User::class)->create();
    actingAs($user);
});

it('returns all authors as a collection of resource objects', function () {
    $authors = factory(Author::class, 3)->create();

    $this->getJson('/api/v1/authors', [
        'accept' => 'application/vnd.api+json',
        'content-type' => 'application/vnd.api+json',
    ])->assertStatus(200)->assertJson([
        "data" => [
            [
                "id" => '1',
                "type" => "authors",
                "attributes" => [
                    'name' => $authors[0]->name,
                    'created_at' => $authors[0]->created_at->toJSON(),
                    'updated_at' => $authors[0]->updated_at->toJSON(),
                ]
            ],
            [
                "id" => '2',
                "type" => "authors",
                "attributes" => [
                    'name' => $authors[1]->name,
                    'created_at' => $authors[1]->created_at->toJSON(),
                    'updated_at' => $authors[1]->updated_at->toJSON(),
                ]
            ],
            [
                "id" => '3',
                "type" => "authors",
                "attributes" => [
                    'name' => $authors[2]->name,
                    'created_at' => $authors[2]->created_at->toJSON(),
                    'updated_at' => $authors[2]->updated_at->toJSON(),
                ]
            ],
        ]
    ]);
});


it('can create an author from a resource object', function () {

    $this->postJson('/api/v1/authors', [
        'data' => [
            'type' => 'authors',
            'attributes' => [
                'name' => 'John Doe',
            ]
        ]
    ], [
        'accept' => 'application/vnd.api+json',
        'content-type' => 'application/vnd.api+json',
    ])->assertStatus(201)->assertJson([
        "data" => [
            "id" => '1',
            "type" => "authors",
            "attributes" => [
                'name' => 'John Doe',
                'created_at' => now()->setMilliseconds(0)->toJSON(),
                'updated_at' => now()->setMilliseconds(0)->toJSON(),
            ]
        ]
    ])->assertHeader('Location', url('/api/v1/authors/1'));

    $this->assertDatabaseHas('authors', [
        'id' => 1,
        'name' => 'John Doe'
    ]);
});

it('returns an author as a resource object', function () {
    $author = factory(Author::class)->create();
    $user = factory(User::class)->create();

    actingAs($user);

    $this->getJson('/api/v1/authors/1', [
        'accept' => 'application/vnd.api+json',
        'content-type' => 'application/vnd.api+json',
    ])
        ->assertStatus(200)
        ->assertJson([
            "data" => [
                "id" => '1',
                "type" => "authors",
                "attributes" => [
                    'name' => $author->name,
                    'created_at' => $author->created_at->toJSON(),
                    'updated_at' => $author->updated_at->toJSON(),
                ]
            ]

        ]);
});

it('can update an author from a resource object', function () {
    $author = factory(Author::class)->create();

    $this->patchJson('/api/v1/authors/1', [
        'data' => [
            'id' => '1',
            'type' => 'authors',
            'attributes' => [
                'name' => 'Jane Doe',
            ]
        ]
    ], [
        'accept' => 'application/vnd.api+json',
        'content-type' => 'application/vnd.api+json',
    ])->assertStatus(200)->assertJson(['data' => [
        'id' => '1',
        'type' => 'authors',
        'attributes' => [
            'name' => 'Jane Doe',
            'created_at' => now()->setMilliseconds(0)->toJSON(),
            'updated_at' => now()->setMilliseconds(0)->toJSON(),
        ],
    ]]);
    $this->assertDatabaseHas('authors', [
        'id' => 1,
        'name' => 'Jane Doe',
    ]);
});

it('can delete an author through a delete request', function () {
    $author = factory(Author::class)->create();
    $this->delete('/api/v1/authors/1', [], [
        'Accept' => 'application/vnd.api+json',
        'Content-Type' => 'application/vnd.api+json',
    ])->assertStatus(204);
    $this->assertDatabaseMissing('authors', [
        'id' => 1,
        'name' => $author->name,
    ]);
});

it('validates that the type member is given when creating an author', function () {
    $this->postJson('/api/v1/authors', [
        'data' => [
            'type' => '',
            'attributes' => [
                'name' => 'John Doe',
            ]
        ]
    ], [
        'accept' => 'application/vnd.api+json',
        'content-type' => 'application/vnd.api+json',
    ])->assertStatus(422)
        ->assertJson([
            'errors' => [
                [
                    'title' => 'Validation Error',
                    'details' => 'The data.type field is required.',
                    'source' => [
                        'pointer' => '/data/type',
                    ]
                ]
            ]
        ]);



    $this->assertDatabaseMissing('authors', [
        'id' => 1,
        'name' => 'John Doe'
    ]);
});

it('validates that the type member has the value of authors when creating an author', function () {
    $this->postJson('/api/v1/authors', [
        'data' => [
            'type' => 'author',
            'attributes' => [
                'name' => 'John Doe',
            ]
        ]
    ], [
        'accept' => 'application/vnd.api+json',
        'content-type' => 'application/vnd.api+json',
    ])
        ->assertStatus(422)
        ->assertJson([
            'errors' => [
                [
                    'title' => 'Validation Error',
                    'details' => 'The selected data.type is invalid.',
                    'source' => [
                        'pointer' => '/data/type',
                    ]
                ]
            ]
        ]);
    $this->assertDatabaseMissing('authors', [
        'id' => 1,
        'name' => 'John Doe'
    ]);
});

it('validates that the attributes member has been given when creating an author', function () {
    $this->postJson('/api/v1/authors', [
        'data' => [
            'type' => 'authors',
        ]
    ], [
        'accept' => 'application/vnd.api+json',
        'content-type' => 'application/vnd.api+json',
    ])
        ->assertStatus(422)
        ->assertJson([
            'errors' => [
                [
                    'title' => 'Validation Error', 'details' => 'The data.attributes field is required.',
                    'source' => [
                        'pointer' => '/data/attributes',
                    ]
                ]
            ]
        ]);

    $this->assertDatabaseMissing('authors', [
        'id' => 1,
        'name' => 'John Doe'
    ]);
});

it('validates that the attributes member is an object given when creating an author', function () {
    $this->postJson('/api/v1/authors', [
        'data' => [
            'type' => 'authors',
            'attributes' => 'not an object',
        ]
    ], [
        'accept' => 'application/vnd.api+json',
        'content-type' => 'application/vnd.api+json',
    ])->assertStatus(422)
        ->assertJson([
            'errors' => [
                [
                    'title' => 'Validation Error',
                    'details' => 'The data.attributes must be an array.',
                    'source' => [
                        'pointer' => '/data/attributes',
                    ]
                ]
            ]
        ]);
    $this->assertDatabaseMissing('authors', [
        'id' => 1,
        'name' => 'John Doe'
    ]);
});
it('validates that a name attribute is given when creating an author', function () {
    $this->postJson('/api/v1/authors', [
        'data' => [
            'type' => 'authors',
            'attributes' => [
                'name' => '',
            ],
        ]
    ], [
        'accept' => 'application/vnd.api+json',
        'content-type' => 'application/vnd.api+json',
    ])
        ->assertStatus(422)
        ->assertJson([
            'errors' => [
                [
                    'title' => 'Validation Error',
                    'details' => 'The data.attributes.name field is required.',
                    'source' => [
                        'pointer' => '/data/attributes/name',
                    ]
                ]
            ]
        ]);

    $this->assertDatabaseMissing('authors', [
        'id' => 1,
        'name' => 'John Doe'
    ]);
});

it('validates that a name attribute is a string when creating an author', function () {
    $this->postJson('/api/v1/authors', [
        'data' => [
            'type' => 'authors',
            'attributes' => [
                'name' => 47,
            ],
        ]
    ], [
        'accept' => 'application/vnd.api+json',
        'content-type' => 'application/vnd.api+json',
    ])
        ->assertStatus(422)
        ->assertJson([
            'errors' => [
                [
                    'title' => 'Validation Error',
                    'details' => 'The data.attributes.name must be a string.',
                    'source' => [
                        'pointer' => '/data/attributes/name',
                    ]
                ]
            ]
        ]);

    $this->assertDatabaseMissing('authors', [
        'id' => 1,
        'name' => 'John Doe'
    ]);
});

it('validates that an id member is given when updating an author', function () {
    $author = factory(Author::class)->create();
    $this->patchJson('/api/v1/authors/1', [
        'data' => [
            'type' => 'authors',
            'attributes' => [
                'name' => 'Jane Doe',
            ]
        ]
    ], [
        'accept' => 'application/vnd.api+json',
        'content-type' => 'application/vnd.api+json',
    ])
        ->assertStatus(422)
        ->assertJson([
            'errors' => [
                [
                    'title' => 'Validation Error',
                    'details' => 'The data.id field is required.',
                    'source' => [
                        'pointer' => '/data/id',
                    ]
                ]
            ]
        ]);
    $this->assertDatabaseHas('authors', [
        'id' => 1,
        'name' => $author->name,
    ]);
});

it('validates that an id member is a string when updating an author', function () {
    $author = factory(Author::class)->create();
    $this->patchJson('/api/v1/authors/1', [
        'data' => [
            'id' => 1,
            'type' => 'authors',
            'attributes' => [
                'name' => 'Jane Doe',
            ]
        ]
    ], [
        'accept' => 'application/vnd.api+json',
        'content-type' => 'application/vnd.api+json',
    ])
        ->assertStatus(422)
        ->assertJson([
            'errors' => [
                [
                    'title' => 'Validation Error',
                    'details' => 'The data.id must be a string.',
                    'source' => [
                        'pointer' => '/data/id',
                    ]
                ]
            ]
        ]);
    $this->assertDatabaseHas('authors', [
        'id' => 1,
        'name' => $author->name,
    ]);
});

it('validates that the type member is given when updating an author', function () {
    $author = factory(Author::class)->create();
    $this->patchJson('/api/v1/authors/1', [
        'data' => [
            'id' => '1',
            'type' => '',
            'attributes' => [
                'name' => 'Jane Doe',
            ]
        ]
    ], [
        'accept' => 'application/vnd.api+json',
        'content-type' => 'application/vnd.api+json',
    ])
        ->assertStatus(422)
        ->assertJson([
            'errors' => [
                [
                    'title' => 'Validation Error',
                    'details' => 'The data.type field is required.',
                    'source' => [
                        'pointer' => '/data/type',
                    ]
                ]
            ]
        ]);
    $this->assertDatabaseHas('authors', [
        'id' => 1,
        'name' => $author->name,
    ]);
});

it('validates that the type member has the value of authors when updating an author', function () {
    $author = factory(Author::class)->create();
    $this->patchJson('/api/v1/authors/1', [
        'data' => [
            'id' => '1',
            'type' => 'author',
            'attributes' => [
                'name' => 'Jane Doe',
            ]
        ]
    ], [
        'accept' => 'application/vnd.api+json',
        'content-type' => 'application/vnd.api+json',
    ])
        ->assertStatus(422)
        ->assertJson([
            'errors' => [
                [
                    'title' => 'Validation Error',
                    'details' => 'The selected data.type is invalid.',
                    'source' => [
                        'pointer' => '/data/type',
                    ]
                ]
            ]
        ]);
    $this->assertDatabaseHas('authors', [
        'id' => 1,
        'name' => $author->name,
    ]);
});

it('validates that the attributes member has been given when updating an author', function () {
    $author = factory(Author::class)->create();
    $this->patchJson('/api/v1/authors/1', [
        'data' => [
            'id' => '1',
            'type' => 'authors',
        ]
    ], [
        'accept' => 'application/vnd.api+json',
        'content-type' => 'application/vnd.api+json',
    ])
        ->assertStatus(422)
        ->assertJson([
            'errors' => [
                [
                    'title' => 'Validation Error',
                    'details' => 'The data.attributes field is required.',
                    'source' => [
                        'pointer' => '/data/attributes',
                    ]
                ]
            ]
        ]);
    $this->assertDatabaseHas('authors', [
        'id' => 1,
        'name' => $author->name,
    ]);
});

it('validates that the attributes member is an object given when updating an author', function () {
    $author = factory(Author::class)->create();
    $this->patchJson('/api/v1/authors/1', [
        'data' => [
            'id' => '1',
            'type' => 'authors',
            'attributes' => 'not an object',
        ]
    ], [
        'accept' => 'application/vnd.api+json',
        'content-type' => 'application/vnd.api+json',
    ])
        ->assertStatus(422)
        ->assertJson([
            'errors' => [
                [
                    'title' => 'Validation Error',
                    'details' => 'The data.attributes must be an array.',
                    'source' => [
                        'pointer' => '/data/attributes',
                    ]
                ]
            ]
        ]);
    $this->assertDatabaseHas('authors', [
        'id' => 1,
        'name' => $author->name,
    ]);
});

it('validates that a name attribute is a string when updating an author', function () {
    $author = factory(Author::class)->create();
    $this->patchJson('/api/v1/authors/1', [
        'data' => [
            'id' => '1',
            'type' => 'authors',
            'attributes' => [
                'name' => 47,
            ],
        ]
    ], [
        'accept' => 'application/vnd.api+json',
        'content-type' => 'application/vnd.api+json',
    ])
        ->assertStatus(422)
        ->assertJson([
            'errors' => [
                [
                    'title' => 'Validation Error',
                    'details' => 'The data.attributes.name must be a string.',
                    'source' => [
                        'pointer' => '/data/attributes/name',
                    ]
                ]
            ]
        ]);
    $this->assertDatabaseHas('authors', [
        'id' => 1,
        'name' => $author->name,
    ]);
});

it('it can sort authors by name through a sort query parameter', function () {
    $authors = collect(['Bertram', 'Claus', 'Anna',])->map(fn ($name) => factory(Author::class)->create(['name' => $name]));

    $this->getJson('/api/v1/authors?sort=name', [
        'accept' => 'application/vnd.api+json',
        'content-type' => 'application/vnd.api+json'
    ])
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                [
                    "id" => '3',
                    "type" => "authors",
                    "attributes" => [
                        'name' => 'Anna',
                        'created_at' => $authors[2]->created_at->toJSON(),
                        'updated_at' => $authors[2]->updated_at->toJSON(),
                    ]
                ],
                [
                    "id" => '1',
                    "type" => "authors",
                    "attributes" => [
                        'name' => 'Bertram',
                        'created_at' => $authors[0]->created_at->toJSON(),
                        'updated_at' => $authors[0]->updated_at->toJSON(),
                    ]
                ],
                [
                    "id" => '2',
                    "type" => "authors",
                    "attributes" => [
                        'name' => 'Claus',
                        'created_at' => $authors[1]->created_at->toJSON(),
                        'updated_at' => $authors[1]->updated_at->toJSON(),
                    ]

                ]
            ]
        ]);
});

it('can sort authors by name in descending order through a sort query parameter', function () {
    $authors = collect([
        'Bertram',
        'Claus',
        'Anna',
    ])->map(function ($name) {
        return factory(Author::class)->create([
            'name' => $name
        ]);
    });
    $this->get('/api/v1/authors?sort=-name', [
        'accept' => 'application/vnd.api+json',
        'content-type' => 'application/vnd.api+json',
    ])->assertStatus(200)->assertJson([
        "data" => [
            [
                "id" => '2',
                "type" => "authors",
                "attributes" => [
                    'name' => 'Claus',
                    'created_at' => $authors[1]->created_at->toJSON(),
                    'updated_at' => $authors[1]->updated_at->toJSON(),
                ]
            ],
            [
                "id" => '1',
                "type" => "authors",
                "attributes" => [
                    'name' => 'Bertram',
                    'created_at' => $authors[0]->created_at->toJSON(),
                    'updated_at' => $authors[0]->updated_at->toJSON(),
                ]
            ],
            [
                "id" => '3',
                "type" => "authors",
                "attributes" => [
                    'name' => 'Anna',
                    'created_at' => $authors[2]->created_at->toJSON(),
                    'updated_at' => $authors[2]->updated_at->toJSON(),
                ]
            ],
        ]
    ]);
});

it('can paginate authors through a page query parameter', function () {
    $authors = factory(Author::class, 10)->create();
    $this->getJson('/api/v1/authors?page[size]=5&page[number]=1', [
        'accept' => 'application/vnd.api+json',
        'content-type' => 'application/vnd.api+json',
    ])->assertStatus(200)->assertJson([
        "data" => [
            [
                "id" => '1',
                "type" => "authors",
                "attributes" => [
                    'name' => $authors[0]->name,
                    'created_at' => $authors[0]->created_at->toJSON(),
                    'updated_at' => $authors[0]->updated_at->toJSON(),
                ]
            ],
            [
                "id" => '2',
                "type" => "authors",
                "attributes" => [
                    'name' => $authors[1]->name,
                    'created_at' => $authors[1]->created_at->toJSON(),
                    'updated_at' => $authors[1]->updated_at->toJSON(),
                ]
            ],
            [
                "id" => '3',
                "type" => "authors",
                "attributes" => [
                    'name' => $authors[2]->name,
                    'created_at' => $authors[2]->created_at->toJSON(),
                    'updated_at' => $authors[2]->updated_at->toJSON(),
                ]
            ],
            [
                "id" => '4',
                "type" => "authors",
                "attributes" => [
                    'name' => $authors[3]->name,
                    'created_at' => $authors[3]->created_at->toJSON(),
                    'updated_at' => $authors[3]->updated_at->toJSON(),
                ]
            ],
            [
                "id" => '5',
                "type" => "authors",
                "attributes" => [
                    'name' => $authors[4]->name,
                    'created_at' => $authors[4]->created_at->toJSON(),
                    'updated_at' => $authors[4]->updated_at->toJSON(),
                ]
            ],
        ],
        'links' => [
            'first' => route('authors.index', ['page[size]' => 5, 'page[number]' => 1]),
            'last' => route('authors.index', ['page[size]' => 5, 'page[number]' => 2]),
            'prev' => null,
            'next' => route('authors.index', ['page[size]' => 5, 'page[number]' => 2]),
        ]
    ]);
});
