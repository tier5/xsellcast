<?php namespace App\Http\Controllers\Admin\Dealers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DealerStoreCsvRequest;
use App\Storage\Crud\Crud;
use App\Storage\Dealer\Dealer;
use App\Storage\Dealer\DealerRepository;
use App\Storage\Media\MediaRepository;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DealersCsvController extends Controller {
    protected $dealer;
    protected $media;
    public function __construct(DealerRepository $dealer, MediaRepository $media) {
        $this->crud   = new Crud();
        $this->dealer = $dealer;
        $this->media  = $media;
    }

    // public function index(Request $request) {
    //     try {
    //         $layoutColumns = $this->crud->layoutColumn();

    //         $layoutColumns->addItemForm('App\Storage\DealerCsv\DealerCsvCrud@createForm');
    //         $this->crud->setExtra('sidemenu_active', 'admin_dealer');

    //         return $this->crud->pageView($layoutColumns);

    //     } catch (\Exception $e) {
    //         $request->session()->flash('message', $e->getMessage());
    //         return redirect()->back();
    //     }
    // }

    public function create(Request $request) {
        try {

            $layoutColumns = $this->crud->layoutColumn();

            $layoutColumns->addItemForm('App\Storage\DealerCsv\DealerCsvCrud@createForm');
            $this->crud->setExtra('sidemenu_active', 'admin_dealer');

            return $this->crud->pageView($layoutColumns);
        } catch (\Exception $e) {
            $request->session()->flash('message', $e->getMessage());
            return redirect()->back();
        }
    }

    public function store(DealerStoreCsvRequest $request) {
        try {
            $csv    = $request->get('csv');
            $medias = $this->media->skipPresenter()->findWhereIn('id', [$csv]);

            foreach ($medias as $media) {
                // dd($media);
                // $csv_url = $media->getOrigUrl();
                $csv_url = 'public/uploads/' . $media->slug;
                Excel::load($csv_url)->each(function (Collection $csvLine) {

                    // dd($csvLine);
                    if ($csvLine->name != '') {

                        $logo = null;
                        $wpid = (int) $csvLine->id;
                        // dd($wpid);
                        $brand    = (int) $csvLine->brandid;
                        $name     = $csvLine->name;
                        $address1 = $csvLine->address;
                        $city     = $csvLine->city;
                        $state    = $csvLine->state;
                        $county   = $csvLine->county;
                        $ziparr   = explode('-', $csvLine->zip_code);
                        $zip      = (int) $ziparr[0];

                        $country            = $csvLine->country;
                        $hours_of_operation = ''; //$csvLine->hours_of_operation;
                        $rep_email          = $csvLine->email_address;
                        $phone              = $csvLine->phone_number;
                        $website            = $csvLine->website == '' ? null : $csvLine->website;
                        $outlet             = $csvLine->outlet == '' ? null : $csvLine->outlet;

                        $desc             = '';
                        $fax              = '';
                        $address2         = '';
                        $distributor_name = '';
                        $rep_name         = '';

                        $dealer = Dealer::where('wpid', '=', $wpid)->first();

                        $data = [
                            'wpid'               => $wpid,
                            'name'               => $name,
                            'description'        => $desc,
                            'logo_media_id'      => $logo,
                            'address1'           => $address1,
                            'address2'           => $address2,
                            'hours_of_operation' => $hours_of_operation,
                            'website'            => $website,
                            'fax'                => $fax,
                            'phone'              => $phone,
                            'city'               => $city,
                            'state'              => $state,
                            'zip'                => $zip,
                            'brand'              => $brand,
                            'outlet'             => $outlet,
                            'county'             => $county,
                            'distributor_name'   => $distributor_name,
                            'rep_name'           => $rep_name,
                            'rep_email'          => $rep_email,
                        ];
                        if (!empty($dealer)) {
                            //update
                            $this->dealer->updateOne($dealer, $data);
                        } else {
                            //insert
                            $this->dealer->createOne($data);
                        }
                        // dd();
                        //
                    }
                });
            }

            // var/www/html/xsellcast/http://xsellcast.test/uploads/testing LBT Dealers 5-22-2018 FINAL-38.csv for reading! File does not exis

//              = $medias['data'][0]['url'];
            // $csv_url='';
            //

            // $logo               = $request->get('logo');
            // $name               = $request->get('name');
            // $desc               = $request->get('desc');
            // $website            = $request->get('website');
            // $phone              = $request->get('phone');
            // $fax                = $request->get('fax');
            // $address1           = $request->get('address_1');
            // $address2           = $request->get('address_2');
            // $city               = $request->get('city');
            // $state              = $request->get('state');
            // $zip                = $request->get('zip');
            // $hours_of_operation = $request->get('hours_of_operation');
            // $brand              = $request->get('brand');
            // $outlet             = $request->get('outlet');
            // $county             = $request->get('county');

            // $distributor_name = $request->get('distributor_name');
            // $rep_name         = $request->get('rep_name');
            // $rep_email        = $request->get('rep_email');

            // $this->dealer->createOne([
            //     'name'               => $name,
            //     'description'        => $desc,
            //     'logo_media_id'      => $logo,
            //     'address1'           => $address1,
            //     'address2'           => $address2,
            //     'hours_of_operation' => $hours_of_operation,
            //     'website'            => $website,
            //     'fax'                => $fax,
            //     'phone'              => $phone,
            //     'city'               => $city,
            //     'state'              => $state,
            //     'zip'                => $zip,
            //     'brand'              => $brand,
            //     'outlet'             => $outlet,
            //     'county'             => $county,
            //     'distributor_name'   => $distributor_name,
            //     'rep_name'           => $rep_name,
            //     'rep_email'          => $rep_email,
            // ]);

            $request->session()->flash('message', "The new dealer was successfully added!");
            return redirect()->route('admin.dealers');
        } catch (\Exception $e) {
            $request->session()->flash('message', $e->getMessage());
            return redirect()->back();
        }
    }

}