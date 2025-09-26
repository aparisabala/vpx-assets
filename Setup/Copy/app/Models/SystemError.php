<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;

class SystemError extends Model
{
    
    use BaseTrait;
    protected $table = 'system_errors';

}
