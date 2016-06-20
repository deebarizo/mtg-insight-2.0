<?php namespace App\UseCases;

use App\Models\Event;
use App\Models\EventDeck;
use App\Models\EventDeckCopy;
use App\Models\Card;

use App\Models\Temp1CardMetagame;

use App\Models\MetagameTimePeriod;

class CardMetagameCreator {

	public function create($date) {

		Temp1CardMetagame::getQuery()->delete();

		$setId = 7;

		$metagameTimePeriods = MetagameTimePeriod::take(4)->where('set_id', $setId)->orderBy('id', 'desc')->get();

		$timePeriodMultipliers = [];

		switch (count($metagameTimePeriods)) {
			
			case 1:
				$timePeriodMultipliers[0] = 1;
				break;

			case 2:
				$timePeriodMultipliers[0] = .60;
				$timePeriodMultipliers[1] = .40;
				break;

			case 3:
				$timePeriodMultipliers[0] = .50;
				$timePeriodMultipliers[1] = .30;
				$timePeriodMultipliers[2] = .20;
				break;
			
			default:
				$timePeriodMultipliers[0] = .40;
				$timePeriodMultipliers[1] = .30;
				$timePeriodMultipliers[2] = .20;
				$timePeriodMultipliers[3] = .10;
				break;
		}

		foreach ($metagameTimePeriods as $timePeriodIndex => $metagameTimePeriod) {
			
			$events = Event::where('date', '>=', $metagameTimePeriod->start_date)->where('date', '<=', $metagameTimePeriod->end_date)->get();

			$totalEventPoints = 0;

			foreach ($events as $event) {

				if ($event->name === 'Pro Tour') {

					$eventPoints = 12;

					$event->points = $eventPoints;

					$totalEventPoints += $eventPoints;
				}
				
				if ($event->name === 'Grand Prix') {

					$eventPoints = 9;

					$event->points = $eventPoints;

					$totalEventPoints += $eventPoints;
				}

				if ($event->name === 'SCG Open') {

					$eventPoints = 6;

					$event->points = $eventPoints;

					$totalEventPoints += $eventPoints;
				}

				if ($event->name === 'SCG Classic') {

					$eventPoints = 4;

					$event->points = $eventPoints;

					$totalEventPoints += $eventPoints;
				}

				$event->save();
			}

			foreach ($events as $event) {

				$event = Event::with('event_decks')->find($event->id);

				$numOfDecks = count($event->event_decks);

				$totalMaxSb = 0;

				$totalFinishPoints = EventDeck::where('event_id', $event->id)->sum('finish');	

				$multipliers = [];

				for ($finishIndex = 1; $finishIndex <= $numOfDecks; $finishIndex++) { 
					
					$multipliers[$finishIndex] = ($numOfDecks - $finishIndex + 1) / $totalFinishPoints;

					$multipliers[$finishIndex] = $multipliers[$finishIndex] * $event->points / $totalEventPoints;

					$multipliers[$finishIndex] = $multipliers[$finishIndex] * $timePeriodMultipliers[$timePeriodIndex];
				}

				foreach ($event->event_decks as $deck) {

					$max = [

						'md' => $deck->md_count / 15,
						'sb' => 4
					];

					$copies = EventDeckCopy::with('event_deck')->with('card')->where('event_deck_id', $deck->id)->get();

					foreach ($copies as $copy) {

						$temp1CardMetagame = new Temp1CardMetagame;

						$temp1CardMetagame->event_deck_id = $deck->id;
						$temp1CardMetagame->card_id = $copy->card_id;
						$temp1CardMetagame->quantity = $copy->quantity;
						$temp1CardMetagame->percentage = numFormat($copy->quantity / $max[$copy->role] * 100 * $multipliers[$deck->finish], 2);
						$temp1CardMetagame->role = $copy->role;

						$temp1CardMetagame->save(); 
					}

					$totalMaxSb += $deck->sb_count / 4 * 100;
				}
			}
		}

		$totalMdPercentage = Temp1CardMetagame::where('role', 'md')->sum('percentage');

		if ($totalMdPercentage <= 1499.50 || $totalMdPercentage >= 1500.50) {

			$this->message = 'Total main deck percentage is '.$totalMdPercentage.'. It should be 1500.00.';

			return $this;
		}

		$totalSbPercentage = Temp1CardMetagame::where('role', 'sb')->sum('percentage'); // $totalSbPercentage == $max['sb'] / 4 * 100

		$targetSbPercentage = $totalMaxSb / $numOfDecks;

		$difference = abs($totalSbPercentage - $targetSbPercentage);

		if ($difference > 1) {

			$this->message = 'Total sideboard percentage is '.$totalSbPercentage.'. It should be '.$targetSbPercentage.'.';

			return $this;
		}

		$this->message = 'Success!';

		return $this;
	}

}