<div class="p-4 bg-white" wire:poll>
    @if($fixtures->count())
        @foreach($fixtures as $fixture)
        <div class="border rounded-lg bg-white p-4 mb-4">
            <p class="text-sm text-gray-700">{{ $fixture->tournament }} · {{ $fixture->stage }}</p>
            <div class="flex items-center justify-between mt-2">  
                <div class="border-r w-8/12">
                    <div class="flex items-center justify-between">
                        <div class="flex">
                            <img class="w-6" src="{{ route('image-proxy', ['image' => $fixture->home_team_logo]) }}">
                            <p class="ml-2">{{ $fixture->home_team_name }}</p>
                        </div>
                        @if($fixture->home_team_win !== null)
                        <p class="pr-3">{{ $fixture->home_team_win }}</p>
                        @endif
                    </div>
                    <div class="mt-2 flex items-center justify-between">
                        <div class="flex">
                            <img class="w-6 h-6" src="{{ route('image-proxy', ['image' => $fixture->away_team_logo]) }}">
                            <p class="ml-2">{{ $fixture->away_team_name }}</p>
                        </div>
                        @if($fixture->away_team_win !== null)
                        <p class="pr-3">{{ $fixture->away_team_win }}</p>
                        @endif
                    </div>
                </div>
                <div class="w-4/12 text-center text-sm">
                    @php
                        $startAt = \Carbon\Carbon::parse($fixture->start_at)->setTimezone($fixture->timezone);
                    @endphp
                    @if($startAt <= now())
                    <p class="text-base text-green-700">Live</p>
                    @else
                    <p>{{ $startAt->format('D, F j') }}</p>
                    <p>{{ $startAt->format('h:i A') }}</p>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    @else
    <div class="flex mt-[180px] w-full items-center justify-center">
        <p class="text-xl font-light text-gray-600">Empty</p>
    </div>
    @endif
</div>
