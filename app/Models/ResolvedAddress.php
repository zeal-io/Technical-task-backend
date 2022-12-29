<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResolvedAddress extends Model
{
    use HasFactory;

    public const SOURCE_GOOGLE = 'google';
    public const SOURCE_HERE = 'here';
}
