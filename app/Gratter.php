<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Gratter
 *
 * @property-read \App\User    $users
 * @property-read \App\Message $messages
 * @property integer           $id
 * @property integer           $user_id
 * @property integer           $to
 * @property integer           $message_id
 * @property integer           $year
 * @property \Carbon\Carbon    $created_at
 * @property \Carbon\Carbon    $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Gratter whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Gratter whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Gratter whereTo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Gratter whereMessageId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Gratter whereYear($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Gratter whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Gratter whereUpdatedAt($value)
 * @property integer           $users_id
 * @property integer           $messages_id
 * @method static \Illuminate\Database\Query\Builder|\App\Gratter whereUsersId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Gratter whereMessagesId($value)
 */
class Gratter extends Model
{
    /**
     * @var string
     */
    protected $table = 'gratters';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function messages()
    {
        return $this->belongsTo('App\Message');
    }
}
