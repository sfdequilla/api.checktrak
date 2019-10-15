<?php

namespace App\Http\Controllers;

use App\User;
use App\Check;
use App\Group;
use App\Action;
use App\Branch;
use App\Module;
use App\Company;
use App\Transmittal;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ChecksReceivedNotification;
use App\Notifications\UserRegisteredNotification;
use App\Notifications\ChecksTransmittedNotification;

class TestController extends Controller
{
    protected $module;

    public function __construct()
    {
        $this->module = Module::where('code', 'usr')->first();
    }

    public function index(Request $request, Transmittal $transmittal)
    {
        // return $transmittal->returned;
        return (new ChecksReceivedNotification($transmittal, User::first()))
                ->toMail($transmittal->group->incharge);

    }
}
