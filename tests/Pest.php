<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, DatabaseMigrations::class, RefreshDatabase::class)->in(__DIR__);
