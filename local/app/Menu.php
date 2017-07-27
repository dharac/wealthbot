<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
	protected $table = 'menu';
	protected $primaryKey = 'menuid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['menu_name','menu_unique_name', 'page_ids', 'created_by', 'modified_by', 'created_at', 'updated_at'];
}
