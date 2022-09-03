<?php


namespace MMA\Files\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = ['model_type','model_id','name','folder','type','size','extension','is_default'];


}
