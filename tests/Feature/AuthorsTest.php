<?php

use App\Author;
use App\User;

it('returns all authors as a collection of resource objects', function () {
    $user = factory(User::class)->create();
    actingAs($user);
    $authors = factory(Author::class, 3)->create();

    $this->get('/api/v1/authors')->assertStatus(200)->assertJson([
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
    $user = factory(User::class)->create();
    actingAs($user);

    $this->postJson('/api/v1/authors', [
        'data' => [
            'type' => 'authors',
            'attributes' => [
                'name' => 'John Doe',
            ]
        ]
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

    $this->getJson('/api/v1/authors/1')
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
    $user = factory(User::class)->create();
    actingAs($user);
    $author = factory(Author::class)->create();

    $this->patchJson('/api/v1/authors/1', [
        'data' => [
            'id' => '1',
            'type' => 'authors',
            'attributes' => [
                'name' => 'Jane Doe',
            ]
        ]
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
    $user = factory(User::class)->create();
    actingAs($user);
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
    $user = factory(User::class)->create();

    actingAs($user);
    $this->postJson('/api/v1/authors', [
        'data' => [
            'type' => '',
            'attributes' => [
                'name' => 'John Doe',
            ]
        ]
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

it('validates_that_the_type_member_has_the_value_of_authors_when_creating_an_author', function () {
    $user = factory(User::class)->create();
    actingAs($user);
    $this->postJson('/api/v1/authors', [
        'data' => [
            'type' => 'author',
            'attributes' => [
                'name' => 'John Doe',
            ]
        ]
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
    $user = factory(User::class)->create();
    actingAs($user);
    $this->postJson('/api/v1/authors', [
        'data' => [
            'type' => 'authors',
        ]
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
    $user = factory(User::class)->create();
    actingAs($user);
    $this->postJson('/api/v1/authors', [
        'data' => [
            'type' => 'authors',
            'attributes' => 'not an object',
        ]
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
    $user = factory(User::class)->create();
    actingAs($user);
    $this->postJson('/api/v1/authors', [
        'data' => [
            'type' => 'authors',
            'attributes' => [
                'name' => '',
            ],
        ]
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
    $user = factory(User::class)->create();
    actingAs($user);
    $this->postJson('/api/v1/authors', [
        'data' => [
            'type' => 'authors',
            'attributes' => [
                'name' => 47,
            ],
        ]
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
    $user = factory(User::class)->create();
    actingAs($user);
    $author = factory(Author::class)->create();
    $this->patchJson('/api/v1/authors/1', [
        'data' => [
            'type' => 'authors',
            'attributes' => [
                'name' => 'Jane Doe',
            ]
        ]
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
    $user = factory(User::class)->create();
    actingAs($user);
    $author = factory(Author::class)->create();
    $this->patchJson('/api/v1/authors/1', [
        'data' => [
            'id' => 1,
            'type' => 'authors',
            'attributes' => [
                'name' => 'Jane Doe',
            ]
        ]
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
    $user = factory(User::class)->create();
    actingAs($user);
    $author = factory(Author::class)->create();
    $this->patchJson('/api/v1/authors/1', [
        'data' => [
            'id' => '1',
            'type' => '',
            'attributes' => [
                'name' => 'Jane Doe',
            ]
        ]
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
    $user = factory(User::class)->create();
    actingAs($user);
    $author = factory(Author::class)->create();
    $this->patchJson('/api/v1/authors/1', [
        'data' => [
            'id' => '1',
            'type' => 'author',
            'attributes' => [
                'name' => 'Jane Doe',
            ]
        ]
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
    $user = factory(User::class)->create();
    actingAs($user);
    $author = factory(Author::class)->create();
    $this->patchJson('/api/v1/authors/1', [
        'data' => [
            'id' => '1',
            'type' => 'authors',
        ]
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
    $user = factory(User::class)->create();
    actingAs($user);
    $author = factory(Author::class)->create();
    $this->patchJson('/api/v1/authors/1', [
        'data' => [
            'id' => '1',
            'type' => 'authors',
            'attributes' => 'not an object',
        ]
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
    $user = factory(User::class)->create();
    actingAs($user);
    $author = factory(Author::class)->create();
    $this->patchJson('/api/v1/authors/1', [
        'data' => [
            'id' => '1',
            'type' => 'authors',
            'attributes' => [
                'name' => 47,
            ],
        ]
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
