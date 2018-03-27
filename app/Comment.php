<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Model;

class Comment extends Model
{
    //
    protected $collection = 'Comments';
}
