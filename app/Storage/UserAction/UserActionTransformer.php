<?php

namespace App\Storage\UserAction;

use League\Fractal\TransformerAbstract;
use App\Storage\UserAction\UserAction;
use Illuminate\Http\Request;

/**
 * Class UserActionTransformer
 * @package namespace App\Storage\UserAction;
 */
class UserActionTransformer extends TransformerAbstract
{

    /**
     * Transform the \UserAction entity
     * @param \UserAction $model
     *
     * @return array
     */
    public function transform(UserAction $model)
    {
        $model->customerOfferRequest();
        return [
            'id'               => (int) $model->id,
            'type'             => $model->type,
            'user'              => $model->user,
            'request_activity' => $model->request_activity, 
            'created_at'       => $model->created_at,
            'updated_at'       => $model->updated_at,
            'user_avatar'      => $model->user->avatar(),
            'created_at_human'  => $this->dateHuman($model->created_at)
        ];
    }

    protected function dateHuman($carbon)
    {

        /**
         * Before anything else change timezone.
         * Set timezone depend on client tz.
         *
         */
        $tz = \Request::cookie('tz');

        if($tz){
            $carbon->setTimezone($tz);
        }

        $today = \Carbon\Carbon::today();
        $weekAgo = \Carbon\Carbon::today()->subWeek();
        $isInWeek = $carbon->between($weekAgo, $today);
        $format = 'd M Y \a\t h:i a';

        if($carbon->isToday())
        {
            $format = '\T\o\d\a\y \a\t h:i a';
        }elseif($carbon->isYesterday()){

            $format = '\Y\e\s\t\e\r\d\a\y \a\t h:i a';
        }elseif($isInWeek){

            $format = 'l \a\t h:i a';
        }

        return $carbon->format($format);        
    }
}
