<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

        /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'services';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'description',
        'className',
        'reference',
        'supportMemory', 
        'supportCaching', 
        'supportCollection', 
        'isActive', 
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'isActive' => 'boolean',
        'supportMemory' => 'boolean',
        'supportCaching' => 'boolean',
        'supportCollection' => 'boolean',
    ];

    public function llms()
    {
        return $this->belongsToMany(LLM::class, 'llm_service', 'service_id', 'llm_id')->withTimestamps();
    }
}
