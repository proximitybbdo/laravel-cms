<?php 

namespace BBDO\Cms\Domain;

use BBDO\Cms\Models;
use Carbon\Carbon;

class ActionLog {

   public static function log($lead,$action,$category_id = null,$data = null,$info = null,$my_score = null){
    $score = 0;
    $action = strtoupper($action);
    $score_cfg = config('scoring.scores.' . $action);
    $item_domain = new PublicItem();
    $categories = $item_domain->getAll('CATEGORY',null,null,null,null, null, false, false,null);
    if($score_cfg != null){
      switch($score_cfg['type']) {
        case "simple":
          self::create($lead,$categories,$action,$category_id,$score_cfg['score'],\Request::ip(),$data,$info);
          break;
        case "distributed":
          foreach($categories as $cat){
            self::create($lead,$categories,$action,$cat->id,$score_cfg['score'],\Request::ip(),$data,$info);
          }
          break;
        case "category":
          foreach($categories as $cat){
            if($cat->description == $score_cfg['category_key']) {
              self::create($lead,$categories,$action,$cat->id,$score_cfg['score'],\Request::ip(),$data,$info);
            }
          }
      } 
    } else {
      self::create($lead,$categories,$action,$category_id,$my_score != null ? $my_score : 0,\Request::ip(),$data,$info);
    }
  }

  private static function create($lead,$categories,$action,$category_id,$score,$ip,$data = null,$info = null){      
    if($lead->has_insurance != null){
      $insurance_arr = explode('|', $lead->has_insurance);
      $insurance_arr = collect($insurance_arr)->map(function ($item, $key) {
          return config('app.categories.' . $item);
      });
      if($insurance_arr->contains($category_id)){
        $score = 0;
        $info = trim($info . ' customer');
      }
    }

    if($score > 0 && strpos($action, \config('app.recurrent_request.search_key')) !== false){
      $count = Models\ActionLog::select('id')
                                ->where('action',$action)
                                ->where('lead_id',$lead->id)
                                ->where('data',$data)
                                ->where('score','>',0)
                                ->where('created_at','>=',Carbon::now()->subMinutes(config('app.recurrent_request.check_minutes')))
                                ->count();
      if($count > 0){
        $info = trim($info . ' recurrent');
        $score = 0;
      }
    }

    $lead_domain = new Lead();
    $lead_domain->score($lead,$categories,$category_id,$score);
    $log = Models\ActionLog::create(array(
      'lead_id' => $lead->id, 
      'action' => $action, 
      'data' => $data, 
      'category_id' => $category_id,
      'score' => $score, 
      'info' => $info, 
      'ip' => $ip,
      'url' => \Request::fullUrl(),
      'referer'=> \Request::server('HTTP_REFERER'),
      'user_agent'=>\Request::server('HTTP_USER_AGENT')  . ' purpose: ' . \Request::server('HTTP_X_PURPOSE')
      ));

    $log->save();
  }

  public static function get_sum_scored($lead,$from_datetime,$to_datetime) {
    $sums = Models\ActionLog::select(\DB::raw('sum(score) as sum_score, category_id'))
                                ->where('score','>',0)
                                ->where('lead_id',$lead->id);
    if($from_datetime != null) {
      $sums = $sums->where('created_at','>=', $from_datetime);
    }

    if($to_datetime != null) {
      $sums = $sums->where('created_at','<', $to_datetime);
    }

    $sums = $sums->groupBy('category_id')->get();
    return $sums->pluck('sum_score','category_id');
  }


}