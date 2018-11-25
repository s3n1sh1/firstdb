<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $tiiranid
 * @property int $tiuserid
 * @property string $tichdt
 * @property string $tichid
 * @property int $tichno
 * @property boolean $tidlfg
 * @property boolean $tidpfg
 * @property float $tiiran
 * @property string $timont
 * @property string $tinote
 * @property string $tiremk
 * @property string $tirgdt
 * @property string $tirgid
 * @property string $tisrce
 * @property string $tistas
 * @property Tbuser $tbuser
 */
class Tbiran extends Model
{
    protected $table = 'tbiran';
    protected $primaryKey = 'tiiranid';
    public $timestamps = false;

    protected $fillable = ['tiuserid', 'tichdt', 'tichid', 'tichno', 'tidlfg', 'tidpfg', 'tiiran', 'timont', 'tinote', 'tiremk', 'tirgdt', 'tirgid', 'tisrce', 'tistas'];

    public function tbuser()
    {
        return $this->belongsTo('App\Tbuser', 'tiuserid', 'tuuserid');
    }
}
