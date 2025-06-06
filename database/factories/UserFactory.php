<?php

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'number' => fake()->phoneNumber(),  
            'password' => static::$password ??= Hash::make('password'),
            'location' => null,  
            'profile_photo_path' => null, 
            'remember_token' => Str::random(10),
        ];
    }
}
