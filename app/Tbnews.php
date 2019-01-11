<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $tnnewsid
 * @property string $tndesc
 * @property string $tnnews
 * @property string $tnremk
 * @property string $tnrgid
 * @property string $tnrgdt
 * @property string $tnchid
 * @property string $tnchdt
 * @property int $tnchno
 * @property boolean $tndlfg
 * @property boolean $tndpfg
 * @property string $tnsrce
 * @property string $tnnote
 */
class Tbnews extends Model
{
    protected $table = 'tbnews';
    protected $primaryKey = 'tnnewsid';
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['tnname', 'tndesc', 'tnremk', 'tnrgid', 'tnrgdt', 'tnchid', 'tnchdt', 'tnchno', 'tndlfg', 'tndpfg', 'tnsrce', 'tnnote'];

}
