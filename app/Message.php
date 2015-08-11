<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Message
 *
 * @property-read \App\User    $users
 * @property-read \App\Gratter $gratters
 * @property integer           $id
 * @property string            $message
 * @property integer           $user_id
 * @property \Carbon\Carbon    $created_at
 * @property \Carbon\Carbon    $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Message whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Message whereMessage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Message whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Message whereUpdatedAt($value)
 * @property integer           $users_id
 * @method static \Illuminate\Database\Query\Builder|\App\Message whereUsersId($value)
 * @property string            $text
 * @method static \Illuminate\Database\Query\Builder|\App\Message whereText($value)
 */
class Message extends Model
{
    /**
     * @var string
     */
    protected $table = 'messages';

    /**
     * @var array
     */
    protected $fillable = ['text'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function gratters()
    {
        return $this->hasMany('App\Gratter');
    }

}
