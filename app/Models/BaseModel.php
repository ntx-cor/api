<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BaseModel extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
    public function table(){
        return (new static)->getTable();
    }
    public function column($column){
        return self::table() . '.' . $column;
    }
    public static function boot() {
        parent::boot();

        static::creating(function($table)
        {
            $table->created_by = $table->updated_by = Auth::user()->id;
            $table->created_at = date('Y-m-d H:i:s');
            $table->updated_at = date('Y-m-d H:i:s');
        });
        // create a event to happen on updating
        static::updating(function($table)  {
            $table->updated_by = Auth::user()->id;
            $table->updated_at = date('Y-m-d H:i:s');
        });

        // create a event to happen on deleting
        static::deleting(function($table)  {
            $table->deleted_by = Auth::user()->id;
        });

        // create a event to happen on saving
        static::saving(function($table)  {
            if(empty($table->created_by)){
                $table->created_by = Auth::user()->id;
                $table->created_at = date('Y-m-d H:i:s');
            }
            $table->updated_by = Auth::user()->id;
            $table->updated_at = date('Y-m-d H:i:s');
        });
        static::retrieved(function ($model) {

        });
    }
}
