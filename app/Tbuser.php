<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @property int $tuuserid
 * @property string $tuuser
 * @property string $tupass
 * @property string $tuname
 * @property string $turemk
 * @property string $turgid
 * @property string $turgdt
 * @property string $tuchid
 * @property string $tuchdt
 * @property int $tuchno
 * @property boolean $tudlfg
 * @property boolean $tudpfg
 * @property string $tusrce
 * @property string $tunote
 */
class Tbuser extends Authenticatable implements JWTSubject
{

    protected $table = 'tbuser';
    protected $primaryKey = 'tuuserid';
    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = ['tuuser', 'tupass', 'tuname', 'turemk', 'turgid', 'turgdt', 'tuchid', 'tuchdt', 'tuchno', 'tudlfg', 'tudpfg', 'tusrce', 'tunote'];

    protected $hidden = ['tupass'];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getAuthPassword() {
        return $this->tupass;
    }
}
