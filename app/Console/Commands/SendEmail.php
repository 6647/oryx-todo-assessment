<?php

namespace App\Console\Commands;

use App\Mail\ReminderEmail;
use App\Models\Todo;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEmail extends Command
{
    protected $signature = 'send:email';
    protected $description = 'Send a reminder email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Code to send the email using Laravel's Mail facade or any other mail library
        $todos = Todo::where('due_date', '<', Carbon::now()->subHours(3))->where('reminder', null)->get();
        // $todos = Todo::where('due_date', '<', Carbon::now()->subMinute(1))->where('reminder', null)->get();
        foreach ($todos as $todo) {
            Mail::to($todo->user->email)->send(new ReminderEmail($todo));
            // $todo->reminder = 'sent';
            // $todo->save;
            Todo::where('id', $todo->id)
                ->update(['reminder' => 1]);

        }
        // dd($todos[0]);
        $this->info('Email sent successfully.');
    }
}
