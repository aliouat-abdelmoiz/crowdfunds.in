<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Page
 *
 * @property integer $id 
 * @property string $name 
 * @property string $description 
 * @property string $content 
 * @property boolean $activate 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @method static \Illuminate\Database\Query\Builder|\App\Page whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Page whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Page whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Page whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Page whereActivate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Page whereUpdatedAt($value)
 */
class Page extends Model
{

}
