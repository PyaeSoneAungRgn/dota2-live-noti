<?php

namespace App\Jobs;

use App\Models\Fixture;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;
use Symfony\Component\BrowserKit\HttpBrowser;
use Throwable;

class SyncFixture implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $fixtures = [];
        $machineTimezone = 'UTC';

        try {
            if(PHP_OS_FAMILY == 'Windows') {
                $machineTimezoneHour = Process::run('powershell -command "(Get-TimeZone).BaseUtcOffset.Hours"')->output();
                $machineTimezoneMinute = Process::run('powershell -command "(Get-TimeZone).BaseUtcOffset.Minutes"')->output();
                $machineTimezone = '+' . Str::replace(["\r", "\n"], ['', ''], $machineTimezoneHour) . Str::replace(["\r", "\n"], ['', ''], $machineTimezoneMinute);
            } else {
                $machineTimezone = Process::run('date +%Z');
                $machineTimezone = Str::replace(["\r", "\n"], ['', ''], $machineTimezone->output());
            }
        } catch (Throwable $e) {
            report($e);
        }

        $client = new HttpBrowser();
        $dom = $client->request('GET', 'https://liquipedia.net/dota2/Liquipedia:Upcoming_and_ongoing_matches');
        $tables = $dom->filter('table.infobox_matches_content');
        foreach ((range(0, $tables->count())) as $index) {
            $fixture = [];
            $continue = true;

            $table = $tables->eq($index);

            if ($table->filter('.team-left > span > span')->eq(1)->filter('a')->count() == 0) {
                $continue = false; // maybe TBD
            }

            if ($table->filter('.team-right > span > span')->eq(1)->filter('a')->count() == 0) {
                $continue = false; // maybe TBD
            }

            if ($table->filter('.versus > div')->count() == 0) {
                $continue = false;
            }

            if ($continue) {
                $fixture['home_team_name'] = $table->filter('.team-left')->text();
                $fixture['home_team_logo'] = $table->filter('.team-left > span > span')->eq(1)->filter('img')->attr('src');

                if ($table->filter('.versus > div')->eq(0)->text() != 'vs') {
                    $fixture['home_team_win'] = Str::before($table->filter('.versus > div')->eq(0)->text(), ':');
                    $fixture['away_team_win'] = Str::after($table->filter('.versus > div')->eq(0)->text(), ':');
                }

                $fixture['away_team_name'] = $table->filter('.team-right')->text();
                $fixture['away_team_logo'] = $table->filter('.team-right > span > span')->eq(1)->filter('img')->attr('src');

                $fixture['stage'] = Str::replace(['(', ')'], ['', ''], $table->filter('.versus > div')->eq(1)->text());
                $fixture['tournament'] = $table->filter('.match-filler > div > div > a')->text();
                $timestamp = (int) $table->filter('.timer-object-countdown-only')->attr('data-timestamp');
                $timezone = $table->filter('.timer-object-countdown-only > abbr')->attr('data-tz');
                $fixture['start_at'] = Carbon::parse($timestamp, $timezone);
                $fixture['timezone'] = $machineTimezone;

                $fixtures[] = $fixture;
            }
        }

        if (count($fixtures) > 0) {
            Fixture::truncate();
            foreach ($fixtures as $fixture) {
                Fixture::query()
                    ->create($fixture);
            }
        }
    }
}
