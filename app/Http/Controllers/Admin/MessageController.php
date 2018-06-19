<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MessageShowGetRequest;
use App\Http\Requests\Admin\MessageShowPostRequest;
use App\Storage\Crud\Crud;
use App\Storage\Messenger\MessageCrud;
use App\Storage\Messenger\MessageRepository;
use App\Storage\Messenger\Thread;
use App\Storage\Messenger\ThreadRepository;
use App\Storage\Offer\OfferRepository;
use Auth;
use Illuminate\Http\Request;

class MessageController extends Controller {

    protected $crud;

    protected $thread;

    protected $offer;

    protected $message;

    public function __construct(ThreadRepository $thread, OfferRepository $offer, MessageRepository $message) {
        $this->crud    = new Crud();
        $this->thread  = $thread;
        $this->offer   = $offer;
        $this->message = $message;
    }

    public function index(Request $request, $type = null) {

        $user          = \Auth::user();
        $layoutColumns = $this->crud->layoutColumn();
        $search        = $request->get('s');
        $thread_count  = $this->message->allMessages($search, $user->id, $type)->skipPresenter()->all()->count();
        $urlParam      = ['type' => $type];

        ///
        //    $this->thread->createSystemMessage($user->id, 'messages.system.ba-welcome', 'Welcome!');
        ///

        if ($search) {
            $urlParam['s'] = urlencode($search);
        }

        $url = ($type ? route('admin.api.messages', $urlParam) : route('admin.api.messages', $urlParam));
        $tbl = MessageCrud::ajaxTable($url);

        $layoutColumns->addItem('admin.messages.actions', ['show_box' => false, 'column_class' => 'm-b-md', 'column_size' => 12]);
        $layoutColumns->addItem('admin.messages.list', ['show_box' => false, 'view_args' => compact('thread_count', 'tbl'), 'column_size' => 12]);
        $layoutColumns->addItem('admin.messages.bottom_box', ['show_box' => false, 'column_class' => 'm-b-sm']);

        return $this->crud->pageView($layoutColumns, compact('type'));
    }

    public function sent(Request $request) {
        $search   = $request->get('s');
        $user     = \Auth::user();
        $urlParam = [];

        $layoutColumns = $this->crud->layoutColumn();

        if ($search) {
            $urlParam['s'] = urlencode($search);
        }

        $thread_count = $this->message->byUser($user->id)->notNote()->skipPresenter()->searchRec($search)->all()->count();
        $tbl          = MessageCrud::ajaxTable(route('admin.api.messages.sent', $urlParam));
        $boxTitle     = 'Sent';

        $layoutColumns->addItem('admin.messages.actions',
            ['show_box' => false, 'column_class' => 'm-b-md', 'column_size' => 12]);
        $layoutColumns->addItem('admin.messages.list',
            ['show_box' => false, 'view_args' => compact('thread_count', 'tbl', 'boxTitle'), 'column_size' => 12]);
        $layoutColumns->addItem('admin.messages.bottom_box',
            ['show_box' => false, 'column_class' => 'm-b-sm']);

        return $this->crud->pageView($layoutColumns);
    }

    public function draft(Request $request) {
        $search        = $request->get('s');
        $user          = \Auth::user();
        $layoutColumns = $this->crud->layoutColumn();
        $thread_count  = $this->message->skipPresenter()->allMessages($search, $user->id, null, false, true)->all()->count();
        $boxTitle      = 'Draft';
        $urlParam      = [];

        if ($search) {
            $urlParam['s'] = urlencode($search);
        }

        $tbl = MessageCrud::ajaxTable(route('admin.api.messages.draft', $urlParam));

        $layoutColumns->addItem('admin.messages.actions', ['show_box' => false, 'column_class' => 'm-b-md', 'column_size' => 12]);
        $layoutColumns->addItem('admin.messages.list', ['show_box' => false, 'view_args' => compact('thread_count', 'tbl', 'boxTitle'), 'column_size' => 12]);
        $layoutColumns->addItem('admin.messages.bottom_box', ['show_box' => false, 'column_class' => 'm-b-sm']);

        return $this->crud->pageView($layoutColumns);
    }

    public function show(MessageShowGetRequest $request, $thread_id, $message_id = null) {

        $user              = \Auth::user();
        $thread            = $request->get('thread');
        $layoutColumns     = $this->crud->layoutColumn();
        $showView          = 'admin.messages.show.' . $thread->type;
        $offer             = $thread->offer();
        $offer_thumb       = ($offer ? $offer->getThumbnail() : null);
        $type              = config('lbt.message_types')[$thread->type];
        $messages          = ($message_id ? $thread->messages()->whereIn('id', [$message_id])->get() : $thread->messages()->get());
        $brand             = ($offer ? $offer->brands()->first() : null);
        $isApproved        = false;
        $newLeadParentType = null;
        $isRejected        = false;
        $isFromMe          = ($messages->first()->user_id == $user->id);

        foreach ($messages as $message) {
            $message->markAsRead($user->id);
        }

        if ($thread->type == 'new_lead') {
            /**
             * Get all related customer to BA to message
             *
             */
            $customer    = $thread->users()->where('user_id', '!=', $user->id)->first()->customer;
            $salesrep    = $user->salesRep()->first();
            $custBaPivot = $customer->salesRepsPivot()->where('salesrep_id', $salesrep->id)->first();
            /**
             * Get approve customer to salesrep relation.
             *
             * @var        boolean
             */
            if (!isset($custBaPivot->approved)) {

                abort('402', 'Prospect is not assigned to BA');
            }

            $isApproved        = ($custBaPivot->approved);
            $isRejected        = ($custBaPivot->rejected);
            $isPending         = ($custBaPivot->isPending);
            $t                 = $thread->getMeta('parent_thread_type');
            $newLeadParentType = (isset(config('lbt.message_types')[$t]) ? config('lbt.message_types')[$t] : null);

            $this->crud->setExtra('sidemenu_active', 'admin_prospects');
        } else {
            $this->crud->setExtra('sidemenu_active', 'admin_messages');
        }

        /**
         * @var        App\Storage\User\User $talking_to
         */
        $talkinToParticipant = $thread->participants()->whereNotIn('user_id', [$user->id])->first();
        $talking_to          = ($talkinToParticipant ? $talkinToParticipant->user()->first() : null);
        $showAcceptButton    = true;
        $thread->markAsRead($user->id);
        $layoutColumns->addItem($showView, [
            'show_box'  => false, 'column_class' => 'm-b-sm',
            'view_args' => compact('offer', 'brand', 'type', 'thread', 'messages', 'talking_to', 'offer_thumb', 'user', 'isRejected', 'isApproved', 'isPending', 'newLeadParentType', 'isFromMe', 'message_id', 'showAcceptButton')]);

        return $this->crud->pageView($layoutColumns, compact('isFromMe'));
    }

    public function printEmail(Request $request, $message_id) {
        $user           = \Auth::user();
        $message        = $this->message->skipPresenter()->find($message_id);
        $inParticipants = $message->participants()->where('user_id', $user->id)->count();
        $offer          = $message->thread->offer();
        $offerThumb     = ($offer ? $offer->getThumbnail() : null);
        $brand          = $offer->brands->first();
        $talking_to     = $message->participants()->whereNotIn('user_id', [$user->id])->first()->user()->first();
        $type           = config('lbt.message_types')[$message->thread->type];
        $thread         = $message->thread;
        $isFromMe       = ($message->user_id == $user->id);

        if (!$inParticipants || $inParticipants < 1) {

            abort("You're not allowed to access message.");
        }

        return view('admin.messages.show.print', compact('type', 'isFromMe', 'thread', 'message', 'user', 'offer', 'offerThumb', 'brand', 'talking_to'));
    }

    public function delete(MessageShowGetRequest $request, $thread_id) {
        $thread = $request->get('thread');
        $thread->delete();

        $request->session()->flash('message', 'Messsage has been deleted.');
        return redirect()->route('admin.messages');
    }

    public function deleteMulti(Request $request, $thread_ids) {
        $user      = Auth::user();
        $threadIds = explode(',', $thread_ids);

        $threads = Thread::whereHas('participants', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->whereIn('id', $threadIds)->delete();

        $request->session()->flash('message', 'Messsage has been deleted.');

        $url = $request->get('redirect_to', route('admin.messages'));
        return response()->json(['data' => ['url' => $url]]);
    }

    public function reply(MessageShowPostRequest $request, $thread_id, $message_id = null) {
        $user                    = $request->get('user');
        $thread                  = $request->get('thread');
        $routeParam['thread_id'] = $thread->id;

        $this->message->create([
            'thread_id' => $thread->id,
            'user_id'   => $user->id,
            'body'      => $request->get('message')]);

        if (in_array($thread->type, ['info', 'appt', 'price'])) {
            $thread->request_approved = true;
            $thread->save();
        }

        $request->session()->flash('message', 'Messsage has been sent.');
        return redirect($request->get('redirect_to'));
    }

    public function CTAindex(Request $request) {

        $user          = \Auth::user();
        $layoutColumns = $this->crud->layoutColumn();
        $search        = $request->get('s');
        $thread_count  = $this->message->allCTARequest($search)->skipPresenter()->all()->count();
        // $thread  = $this->message->allCTARequest()->skipPresenter()->all();
        // dd($thread);

        $type = 'appt';

        $urlParam = [];
        if ($search) {
            $urlParam['s'] = urlencode($search);
        }

        $url = route('admin.api.messages.cta.request');
        // dd($url);
        $tbl = MessageCrud::ajaxCTATable($url);

        // $layoutColumns->addItem('admin.messages.actions', ['show_box' => false, 'column_class' => 'm-b-md', 'column_size' => 12]);
        $layoutColumns->addItem('admin.messages.cta_list', ['show_box' => false, 'view_args' => compact('thread_count', 'tbl'), 'column_size' => 12]);
        // $layoutColumns->addItem('admin.messages.bottom_box', ['show_box' => false, 'column_class' => 'm-b-sm']);

        return $this->crud->pageView($layoutColumns, compact('type'));
    }

    public function showCTA(Request $request, $thread_id, $message_id = null) {
        // dd($request->all());
        $user = \Auth::user();
        // $thread            = $request->get('thread');
        $thread        = Thread::find($thread_id);
        $layoutColumns = $this->crud->layoutColumn();
        $showView      = 'admin.messages.show.' . $thread->type;
        $offer         = $thread->offer();
        $cta_brand     = $thread->brand();

        $offer_thumb       = ($offer ? $offer->getThumbnail() : null);
        $type              = config('lbt.message_types')[$thread->type];
        $messages          = ($message_id ? $thread->messages()->whereIn('id', [$message_id])->get() : $thread->messages()->get());
        $brand             = ($offer ? $offer->brands()->first() : null);
        $isApproved        = false;
        $newLeadParentType = null;
        $isRejected        = false;
        $isFromMe          = ($messages->first()->user_id == $user->id);

        // foreach($messages as $message)
        // {
        //     $message->markAsRead($user->id);
        // }

        // if($thread->type == 'new_lead')
        // {
        //     /**
        //      * Get all related customer to BA to message
        //      *
        //      */
        //     $customer    = $thread->users()->where('user_id', '!=', $user->id)->first()->customer;
        //     $salesrep    = $user->salesRep()->first();
        //     $custBaPivot = $customer->salesRepsPivot()->where('salesrep_id', $salesrep->id)->first();
        //     /**
        //      * Get approve customer to salesrep relation.
        //      *
        //      * @var        boolean
        //      */
        //     if(!isset($custBaPivot->approved)){

        //         abort('402', 'Prospect is not assigned to BA');
        //     }

        //     $isApproved        = ($custBaPivot->approved);
        //     $isRejected        = ($custBaPivot->rejected);
        //     $isPending           = ($custBaPivot->isPending);
        //     $t                 = $thread->getMeta('parent_thread_type');
        //     $newLeadParentType = (isset(config('lbt.message_types')[$t]) ? config('lbt.message_types')[$t] : null);

        //     $this->crud->setExtra('sidemenu_active', 'admin_prospects');
        // }else
        // {
        $this->crud->setExtra('sidemenu_active', 'admin_messages');
        // }

        /**
         * @var        App\Storage\User\User $talking_to
         */
        $talkinToParticipant = $thread->participants()->whereNotIn('user_id', [$user->id])->first();
        $talking_to          = ($talkinToParticipant ? $talkinToParticipant->user()->first() : null);
        // $talking_to = null;
        $showAcceptButton = true;
        $thread->markAsRead($user->id);
        $layoutColumns->addItem($showView, [
            'show_box'  => false, 'column_class' => 'm-b-sm',
            'view_args' => compact('offer', 'brand', 'type', 'thread', 'messages', 'talking_to', 'offer_thumb', 'user', 'isRejected', 'isApproved', 'isPending', 'newLeadParentType', 'isFromMe', 'message_id', 'showAcceptButton', 'cta_brand')]);

        return $this->crud->pageView($layoutColumns, compact('isFromMe'));
    }

}
