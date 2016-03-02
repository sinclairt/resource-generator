<?php

namespace App\Models;

use Sinclair\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Contracts\Dummy as DummyInterface;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sinclair\Track\TrackTrait;

class Dummy extends Model implements DummyInterface
{
    use SoftDeletes, CascadeSoftDeletes, TrackTrait;

    /**
     * Child relationships to be deleted when this object is soft deleted
     *
     * @var array
     */
    protected $children = [ ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dummies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [  ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [  ];

    /**
     * The dates that are returned as Carbon objects
     *
     * @var array
     */
    protected $dates = [ 'created_at', 'updated_at', 'deleted_at' ];

    /**
     * These are the fields and their types to be used in create/edit forms
     *
     * @var array
     */
    public $fields = [ ];
}