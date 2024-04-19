<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Validator;

class Country extends Model
{
    use HasFactory;
    public $timestamps = true;    
    protected $table = 'countries';
    protected $fillable = [
        'code', 
        'name',
        'nicename',
        'phonecode'
    ];    

    public static function GetAllCounries(){        
        $countries = Country::orderby('title', 'ASC')->get();
        return $countries;
    }

    public static function GetCountryDetails($id){        
        $country = Country::find($id);
        return $country;
    }

    public function states()
    {
        return $this->hasMany(State::class, 'country_id')->orderBy('name');
    }

    public static function GetCountryDetailsByCountryCode($code){        
        $country = Country::where('code', $code)->first();
        return $country;
    }

}