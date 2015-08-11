<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Provider
 *
 * @property-read \App\User $users
 * @property integer        $id
 * @property string         $name
 * @property integer        $uid
 * @property string         $token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereUid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereUpdatedAt($value)
 */
class Provider extends Model
{
    /**
     * @var string
     */
    protected $table = 'providers';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function users()
    {
        return $this->hasOne('App\User');
    }
}
