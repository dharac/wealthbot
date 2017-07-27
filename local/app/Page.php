<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
	protected $table = 'pages';
	protected $primaryKey = 'pageid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'excerpt','content_type', 'content', 'created_by', 'modified_by', 'created_at', 'updated_at'];
}
