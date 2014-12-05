<?php namespace App\Modules\Comment\Models;

use Base;

class Comment extends Base
{
    protected $table = 'comments';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $guarded = array('id');
    protected $fillable = array(
        'model_id',
        'model_type',
        'user_id',
        'ip',
        'comment',
        'status',
        'parent_id',
        'user_name',
        'email',
    );

    public function model()
    {
        return $this->morphTo();
    }

}