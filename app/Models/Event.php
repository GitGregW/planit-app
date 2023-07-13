<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'contact_mobile' => 'string',
        'contact_landline' => 'string',
    ];

    public function getRouteKey(): mixed
    {
        return $this->slug;
    }

    public function path()
    {
        return '/events/' . $this->slug;
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function eventSchedules(): HasMany
    {
        return $this->hasMany(EventSchedule::class);
    }

    public function eventBookings(): HasMany
    {
        return $this->hasMany(EventBooking::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function getImages()
    {
        return Storage::disk('event_images')->files($this->id);
    }

    public function deleteImages()
    {
        return Storage::disk('event_images')->deleteDirectory($this->id);
    }

    // public function addTags($tags)
    // {
    //     if(is_string($tags)) return $this->Tags()->create(
    //         ['slug' => strtolower(str_replace(' ','-',$tags))],
    //         ['name' => $tags]
    //     );
    //     foreach($tags as $key => $tag){
    //         $tags[$key]['slug'] = strtolower(str_replace(' ','-',$tag['name']));
    //     }
    //     return $this->Tags()->createMany($tags);
    // }

    // public function deleteTags($eventTagIds)
    // {
    //     return $this->tags()->detach($eventTagIds);
    // }
}
