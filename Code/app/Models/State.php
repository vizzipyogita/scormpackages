<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Validator;

class State extends Model
{
    use HasFactory;
    public $timestamps = true;    
    protected $table = 'states';
    protected $fillable = [
        'country_id', 
        'name',
        'code',
    ];    

}