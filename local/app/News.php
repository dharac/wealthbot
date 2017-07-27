<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';
    protected $primaryKey = 'newsid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['news_header', 'excerpt','news_description', 'status', 'created_by', 'modified_by', 'created_at', 'updated_at'];
}
