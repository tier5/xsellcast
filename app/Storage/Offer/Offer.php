<?php

namespace App\Storage\Offer;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Kodeine\Metable\Metable;
use App\Storage\Media\Media;
use Auth;

class Offer extends Model implements Transformable
{
    use TransformableTrait, Metable;

    protected $fillable = ['contents', 'title', 'wpid', 'status', ' author_type', 'original_source_url','wp_offer_link','media_link'];

    protected $table = 'offers';

    public function brands()
    {
    	return $this->belongsToMany('App\Storage\Brand\Brand', 'brand_offers', 'offer_id', 'brand_id');
    }

    public function customers()
    {
    	return $this->belongsToMany('App\Storage\Customer\Customer', 'customer_offers', 'offer_id', 'customer_id');
    }

    public function salesrep()
    {
        return $this->belongsToMany('App\Storage\SalesRep\SalesRep', 'salesrep_offers', 'offer_id', 'salesrep_id');
    }

//    public function customerSalesRepInfos()
//    {
      //  return $this->hasMany('App\Storage\CustomerSalesrepInfo\CustomerSalesrepInfo', 'offer_id', 'id');
//    }

    /**
     * List salesrep from related dealer to offer
     *
     * @return     Array
     */
    public function salesrepLists()
    {
        $list = [];

        foreach($this->brands->first()->dealers->first()->salesReps as $salesrep)
        {
            $dealer = $salesrep->dealers->first();

            $state = $dealer->state; //(isset(states()[$dealer->state]) ? states()[$dealer->state] : '' );
            $list[$salesrep->id] = $salesrep->user->firstname. ', ' . $salesrep->user->lastname . ' - ' . $dealer->city . ', ' . $state;
        }

        return $list;
    }

    /**
     * This will select record from relationship table('customer_offers')
     */
    public function pivotCustomers()
    {
        return $this->hasMany('App\Storage\CustomerOffer\CustomerOffer', 'offer_id', 'id');
    }

    public function setCustomer($customer_id)
    {
        return $this->pivotCustomers()
            ->updateOrCreate(['customer_id' => $customer_id, 'offer_id' => $this->id])
            ->offer();
    }

    public function tags()
    {
        return $this->hasMany('App\Storage\OfferTag\OfferTag', 'offer_id', 'id');
    }

    public function medias()
    {
        $mediaMeta = $this->getMeta('media');

        if(is_array($mediaMeta)){
            return Media::whereIn('id', $mediaMeta);
        }

        return null;
    }

    public function humanStatus()
    {

        if($this->status == 'publish'){

            return 'Published';
        }elseif($this->status == 'draft'){

            return 'Unpublished';
        }else{

            return 'Pending';
        }
    }

    /**
     * @return  string  return Monday on 06:30AM or MAR 28 2017 on 06:30A
     */
    public function createdAtDayOrDate()
    {
        $today = \Carbon\Carbon::today();
        $weekAgo = \Carbon\Carbon::today()->subWeek();
        $isInWeek = $this->created_at->between($weekAgo, $today);

        if($isInWeek){

          //  return $this->created_at->format('l \o\n h:iA');
        }else{

          //  return $this->created_at->format('d M Y \o\n h:iA');
        }

        return $this->created_at->format('M d, Y \o\n h:i A');
    }

    public function getThumbnail()
    {
        $medias = $this->medias();
        $thumb =  ($medias ? $medias->where('id', $this->getMeta('thumbnail_id')) : null);

        if($thumb){
            return $thumb->first();
        }

        return null;
    }

    /**
     * String
     */
    public function lbtUrl()
    {

        return route('lbt.offer', ['wp_post_id' => ($this->wpid ? $this->wpid : 0)]); //config('lbt.wp_site') . '?p=' . ($this->wpid ? $this->wpid : 0);
    }

    public function scopeInDealers($query, $dealers_id)
    {
        return $query->whereHas('brands', function($query) use($dealers_id){
            $query->whereHas('dealers', function($query) use($dealers_id){
                $query->whereIn('dealers.id', $dealers_id);
            });
        });
    }

    public function scopeInSalesreps($query, $salesreps_id)
    {
        return  $query->whereHas('salesrep', function($query) use($salesreps_id){
                    $query->whereIn('salesrep_offers.salesrep_id', $salesreps_id);
                });

    }

    public function isEditable()
    {

        return ($this->author_type == 'custom');
    }

    public function isDeletable()
    {
        return ($this->author_type == 'custom');
    }
}
