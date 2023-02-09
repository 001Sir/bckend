<?php

namespace App\Console\Commands;

use App\Mail\InvoiceMade;
use App\Models\Invoice;
use App\Models\Partner;
use App\Repository\API\V1\Notifications\NotificationMessagingRepository;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class InvoiceCreationOneWeekPriorNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:oneweek_notice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a one week prior notice to partners that have subscribed.';

    private NotificationMessagingRepository $notificationMessagingRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->notificationMessagingRepository = new NotificationMessagingRepository();
        $partnersToBeInformed = Partner::whereHas('package', function ($query) {
            $query->whereBetween('valid_until', [Carbon::now()->toDateString(), Carbon::now()->addWeek()->toDateString()]);
        })->get();

        foreach($partnersToBeInformed as $partner) {
            $partnerModel = Partner::with('package')->where('id', $partner->id)->first();
            if($partner->package()->first()->price > 0) {
                Invoice::create(['partner_id' => $partner->id, 'package_id' => $partner->package()->first()->id, 'invoiced_date' => Carbon::now(), 'due_date' => Carbon::now()->addWeek(), 'amount' => $partner->package()->first()->price, 'is_paid' => false]);
                Mail::to($partner->email)->send(new InvoiceMade($partner->package()->first()->name, $partner->package()->first()->price));
                $this->notificationMessagingRepository->sendNotificationToPartner([
                    'title' => 'New invoice created',
                    'body' => 'New invoice for this month has been billed.'
                ], $partnerModel);
            }
        }
    }
}
