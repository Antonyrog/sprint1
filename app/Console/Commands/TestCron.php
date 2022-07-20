<?php

namespace App\Console\Commands;

use App\Mail\SendMailable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

use App\Models\Articulo;
use App\Models\User;
use Spatie\Permission\Traits\HasRoles;

class TestCron extends Command
{
    protected $signature = 'test:cron';

    protected $description = 'This is a cron to log message';
   
    public function __construct()
    {
        parent::__construct();
    }
    
    public function handle()
    {
        $articulo = Articulo::all();

        $users = User::role(['Administrador', 'Vendedor'])->get();

        foreach ($articulo as $articulos) {
            if ($articulos->stock <= 10) {
                foreach ($users as $userss) {
                    Mail::to($userss)->send(new 
                    SendMailable($articulos));
                    Log::info("Successfully, cron is running");
                }
            }
        }
    }
}