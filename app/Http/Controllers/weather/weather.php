<?php
namespace App\Http\Controllers\weather;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Response;
use Illuminate\Support\Facades\URL;
use App\Models\WeatherModel;
use Validator, Input, Redirect;
use App\User;
use Auth;
use Carbon\Carbon;

class weather extends Controller
{
  public function getWeatherInformation(Request $request)
  {
    date_default_timezone_set('Asia/Calcutta');
    $current_date_time = date("Y-m-d H:i:s");

    $weather_information = 'http://api.openweathermap.org/data/2.5/weather?q=america,usa&APPID=05185118554267d151dcd7036286a1ac';

    $apiKey = "05185118554267d151dcd7036286a1ac";
    $cityId = "chandigarh";
    $googleApiUrl = "http://api.openweathermap.org/data/2.5/weather?q=" . $cityId . "&lang=en&units=metric&APPID=" . $apiKey;

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);

    curl_close($ch);
    $data = json_decode($response);
    $currentTime = time();


    if ( isset($data->cod) && $data->cod == "404" ) 
    {
        $message = "No city found";

        echo 'message : '.$message;
    }
    else    
    {
         $check_city_weather_report=DB::table('weather_report')->where('city_name', 'LIKE', "%{$data->name}%")->count();

         if($check_city_weather_report > 0)
         {
           $city_weather_report_data=DB::table('weather_report')->where('city_name', 'LIKE', "%{$data->name}%")->first();

           $weather_report_id = $city_weather_report_data->id;

           $weather_data = WeatherModel::find($weather_report_id);

          $weather_data->city_name = $data->name;
          $weather_data->description = $data->weather[0]->description;
          $weather_data->min_temp = $data->main->temp_min;
          $weather_data->max_temp = $data->main->temp_max;
          $weather_data->humidity = $data->main->humidity;
          $weather_data->speed = $data->wind->speed;
          $weather_data->updated_at = $current_date_time;


          $update_weather_data = $weather_data->update();

          if($update_weather_data)
          {
          return view('weather/weatherReport', compact('data','currentTime'));
          }
         }

         else
         {
          $weather_data = new WeatherModel;

          $weather_data->city_name = $data->name;
          $weather_data->description = $data->weather[0]->description;
          $weather_data->min_temp = $data->main->temp_min;
          $weather_data->max_temp = $data->main->temp_max;
          $weather_data->humidity = $data->main->humidity;
          $weather_data->speed = $data->wind->speed;
          $weather_data->created_at = $current_date_time;
          $weather_data->updated_at = $current_date_time;

          $insert_weather_data = $weather_data->save();

          if($insert_weather_data)
          {
          return view('weather/weatherReport', compact('data','currentTime'));
          }
         }

         
    }
  }
  

}
