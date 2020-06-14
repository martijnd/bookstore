<?php

use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Passport\Passport;
use Tests\TestCase;

/**
 * Set the currently logged in user for the application.
 */
function actingAs(Authenticatable $user): Authenticatable
{
    return Passport::actingAs($user);
}
