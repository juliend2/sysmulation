<?php declare(strict_types=1);

class Stock {

    private $initial_value;
    private $events;

    public function __construct($initial_value, $events) {
        $this->initial_value = $initial_value;
        $this->events = $events;
    }

    public function getStockValueAt(Time $moment): int {
        $timestamps_with_stocks = $this->timestampsWithStocks();
        return $timestamps_with_stocks[ strtotime($moment->date()) ];
    }

    private function timestampsWithStocks() {
        /*
        1. DONE sort all the events and sort them by start time (early to late)
        2. DONE create an ARRAY OF DAYS (timestamps) between the earliest event and latest event
            [
                '15315136134',
                '15315137456',
                '15315138698',
                etc...
            ]
        3. DONE generate a hash from the array we just created, where each value is a key
            [
                '15315136134' => [],
                '15315137456' => [],
                '15315138698' => [],
                etc...
            ]
        4. DONE for each event
            a) generate the dates and mutations to make those mutations static in time
                [
                    '15315136134' => [
                        -1000 // stock change
                    ],
                    '15315137456' => [
                        -1000 // stock change
                    ],
                    etc...
                ]
        5. generate a new hash like the previous, where all the values are the stock at a particular day
        6. create an int variable of the stock (mutable)
        5. for each day in the generated hash
            a) sequentially calculate the stock, based on a variable that is either incremented or decremented
            b) and add the stock to every day in the hash
        */
        $sorted_events_by_start = $this->events;
        uasort($sorted_events_by_start, function ($event_a, $event_b) {
            return $event_a->minDate() < $event_b->minDate() ? -1 : 1;
        });
        $sorted_events_by_end = $this->events;
        uasort($sorted_events_by_end, function ($event_a, $event_b) {
            return $event_a->maxDate() < $event_b->maxDate() ? -1 : 1;
        });
        $first = $sorted_events_by_start[ array_key_first($sorted_events_by_start) ];
        $last = $sorted_events_by_end[ array_key_last($sorted_events_by_end) ];
        $days_length = $last->daysSince($first);
        $timestamps_kv = [];
        // Will become:
        // [
        //     '1609477200' => [],
        //     '1609563600' => [],
        //     '1609650000' => [],
        //     etc...
        // ]
        for ($i = 0; $i < ($days_length + 1); $i ++) {
            $timestamps_kv[ $first->minTimestamp() + ($i * (60 * 60 * 24))] = [];
        }

        foreach ($timestamps_kv as $timestamp => $_) {
            foreach ($this->events as $evnt) {
                if (strtotime($evnt->minDate()) == $timestamp) {
                    $timestamps_kv[ $timestamp ] []= $evnt->stockChange();
                }
            }
        }

        $timestamps_sums = [];
        // Will become:
        // [
        //   '1609477200' => -1000,
        //   '1609563600' => 0,
        //   '1609650000' => 0,
        // ]
        foreach ($timestamps_kv as $timestamp => $value_changes) {
            $timestamps_sums[ $timestamp ] = array_sum($value_changes);
        }

        $stock_mutated_value = $this->initial_value;
        $timestamps_with_stocks = [];
        foreach ($timestamps_sums as $timestamp => $value_change) {
            if ($value_change !== 0) {
                $stock_mutated_value += $value_change;
            }
            $timestamps_with_stocks[ $timestamp ] = $stock_mutated_value;
        }

        return $timestamps_with_stocks;
    }

}