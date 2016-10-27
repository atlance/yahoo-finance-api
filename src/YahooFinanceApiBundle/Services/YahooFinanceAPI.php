<?php

namespace YahooFinanceApiBundle\Services;

class YahooFinanceAPI
{
    /**
     * Column Aliases.
     *
     * @var array ['alias' => 'key',..]
     */
    private $columnAliases = [
        /* ------------ Pricing  ------------
         * a – ask
         * b – bid
         * b2 – ask (realtime)
         * b3 – bid (realtime)
         * p – previous close
         * o – open
         */
        'Ask' => 'a',
        'Bid' => 'b',
        'AskRealTime' => 'b2',
        'BidRealTime' => 'b3',
        'PreviousClose' => 'p',
        'Open' => 'o',
        /* ------------ Dividends  ------------
         * y – dividend yield
         * d – dividend per share
         * r1 – dividend pay date
         * q – ex-dividend date
         */
        'DividendYield.' => 'y',
        'DividendShare' => 'd',
        'DividendPayDate' => 'r1',
        'ExDividendDate' => 'q',
        /* ------------ Date  ------------
         * c1 – change
         * c – change & percentage change
         * c6 – change (realtime)
         * k2 – change percent
         * p2 – change in percent
         * d1 – last trade date
         * d2 – trade date
         * t1 – last trade time
         */
        'Change' => 'c1',
        'ChangePercentChange' => 'c',
        'ChangeRealTime' => 'c6',
        'ChangePercent' => 'k2',
        'ChangeInPercent' => 'p2',
        'LastTradeDate' => 'd1',
        'TradeDate' => 'd2',
        'LastTradeTime' => 't1',
        /* ------------ Averages ------------
         * c8 – after hours change
         * c3 – commission
         * g – day’s low
         * h – day’s high
         * k1 – last trade (realtime) with time
         * l – last trade (with time)
         * l1 – last trade (price only)
         * t8 – 1 yr target price
         * m5 – change from 200 day moving average
         * m6 – percent change from 200 day moving average
         * m7 – change from 50 day moving average
         * m8 – percent change from 50 day moving average
         * m3 – 50 day moving average
         * m4 – 200 day moving average
         */
        'AfterHoursChange' => 'c8',
        'Commission' => 'c3',
        'DaysLow' => 'g',
        'DaysHigh.' => 'h',
        'LastTradeRealTimeWithTime' => 'k1',
        'LastTradeWithTime' => 'l',
        'LastTradePriceOnly' => 'l1',
        '1yrTargetPrice' => 't8',
        'ChangeFrom200dayMovingAverage' => 'm5',
        'PercentChangeFrom200dayMovingAverage' => 'm6',
        'ChangeFrom50dayMovingAverage' => 'm7',
        'PercentChangeFrom50dayMovingAverage' => 'm8',
        '50dayMovingAverage' => 'm3',
        '200dayMovingAverage' => 'm4',
        /* ------------  Misc ------------
         * w1 – day’s value change
         * w4 – day’s value change (realtime)
         * p1 – price paid
         * m – day’s range
         * m2 – day’s range (realtime)
         * g1 – holding gain percent
         * g3 – annualized gain
         * g4 – holdings gain
         * g5 – holdings gain percent (realtime)
         * g6 – holdings gain (realtime)
         * t7 – ticker trend
         * t6 – trade links
         * i5 – order book (realtime)
         * l2 – high limit
         * l3 – low limit
         * v1 – holdings value
         * v7 – holdings value (realtime)
         * s6 – revenue
         */
        'DaysValueChange' => 'w1',
        'DaysValueChangeRealTime' => 'w4',
        'PricePaid' => 'p1',
        'DaysRange' => 'm',
        'DaysRangeRealTime' => 'm2',
        'HoldingsGainPercent' => 'g1',
        'AnnualizedGain' => 'g3',
        'HoldingsGain' => 'g4',
        'HoldingsGainPercentRealTime' => 'g5',
        'HoldingsGainRealTime' => 'g6',
        'TickerTrend' => 't7',
        'TradeLinks' => 't6',
        'OrderBook' => 'i5',
        'HighLimit' => 'l2',
        'LowLimit' => 'l3',
        'HoldingsValue' => 'v1',
        'HoldingsValueRealTime' => 'v7',
        'Revenue' => 's6',
        /* ------------ 52 Week Pricing ------------
         * k – 52 week high
         * j – 52 week low
         * j5 – change from 52 week low
         * k4 – change from 52 week high
         * j6 – percent change from 52 week low
         * k5 – percent change from 52 week high
         * w – 52 week range
         */
        '52weekHigh' => 'k',
        '52weekLow' => 'j',
        'ChangeFrom52weekLow' => 'j5',
        'ChangeFrom52weekHigh' => 'k4',
        'PercentChangeFrom52weekLow' => 'j6',
        'PercentChangeFrom52weekHigh' => 'k5',
        '52weekRange' => 'w',
        /* ------------ Symbol Info ------------
         * i – more info
         * j1 – market capitalization
         * j3 – market cap (realtime)
         * f6 – float shares
         * n – name
         * n4 – notes
         * s – symbol
         * s1 – shares owned
         * x – stock exchange
         * j2 – shares outstanding
         */
        'MoreInfo' => 'i',
        'MarketCapitalization' => 'j1',
        'MarketCap' => 'j3',
        'FloatShares' => 'f6',
        'Name' => 'n',
        'Notes' => 'n4',
        'Symbol' => 's',
        'SharesOwned' => 's1',
        'StockExchange' => 'x',
        'SharesOutstanding' => 'j2',
        /* ------------ Volume ------------
         * v – volume
         * a5 – ask size
         * b6 – bid size
         * k3 – last trade size
         * a2 – average daily volume
         */
        'Volume' => 'v',
        'AskSize' => 'a5',
        'BidSize' => 'b6',
        'LastTradeSize' => 'k3',
        'AverageDailyVolume' => 'a2',
        /* ------------ Ratios ------------
         * e – earnings per share
         * e7 – eps estimate current year
         * e8 – eps estimate next year
         * e9 – eps estimate next quarter
         * b4 – book value
         * j4 – EBITDA
         * p5 – price / sales
         * p6 – price / book
         * r – P/E ratio
         * r2 – P/E ratio (realtime)
         * r5 – PEG ratio
         * r6 – price / eps estimate current year
         * r7 – price /eps estimate next year
         * s7 – short ratio
         */
        'EarningsShare' => 'e',
        'EPSEstimateCurrentYear' => 'e7',
        'EPSEstimateNextYear' => 'e8',
        'EPSEstimateNextQuarter' => 'e9',
        'BookValue' => 'b4',
        'EBITDA' => 'j4',
        'PriceSales' => 'p5',
        'PriceBook' => 'p6',
        'PERatio' => 'r',
        'PERatioRealTime' => 'r2',
        'PEGRatio' => 'r5',
        'PriceEPSEstimateCurrentYear' => 'r6',
        'PriceEPSEstimateNextYear' => 'r7',
        'ShortRatio' => 's7',
        /* ------------ Other ------------
         * c4 - Currency
         * e1 - Error Indication (returned for symbol changed / invalid)
         * f0 - Trade Links Additional
         */
        'Currency' => 'c4',
        'ErrorIndication' => 'e1',
        'TradeLinksAdditional' => 'f0',
    ];

    /**
     * http://ichart.finance.yahoo.com/table.csv?s={symbol}&{key}={value}.
     *
     * Keys:
     * a - Start Month (0-based; 0=January, 11=December)
     * b - Start Day
     * c - Start Year
     * d - End Month (0-based; 0=January, 11=December)
     * e - End Day
     * f - End Year
     * g - Always use the letter d
     *
     * @param string    $symbol
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     *
     * @return array
     */
    public function fetchChart(string $symbol, \DateTime $startDate = null, \DateTime $endDate = null): array
    {
        $startDate = $startDate ?? new \DateTime('-7 days');
        $endDate  = $endDate ?? new \DateTime();
        $query = http_build_query(
            [
                'a' => -1 + (int) $startDate->format('m'),
                'b' => $startDate->format('d'),
                'c' => $startDate->format('Y'),
                'd' => -1 + (int) $endDate->format('m'),
                'e' => -1 + $endDate->format('d'),
                'f' => $endDate->format('Y'),
                's' => $symbol,
            ]
        );

        $data = $this->curl("http://ichart.finance.yahoo.com/table.csv?$query");

        if ($data) {
            $data = preg_split('/\r\n|\r|\n/', trim($data));
            unset($data[0]);

            return array_values($this->parse($data));
        }

        return [];
    }

    /**
     * @param array     $symbols
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @param bool      $timestamp
     *
     * @return array
     */
    public function fetchPortfolioChart(
        array $symbols,
        \DateTime $startDate,
        \DateTime $endDate,
        bool $timestamp = true
    ): array {
        $portfolioChart = [];
        $stockQuotesChart = [];

        foreach ($symbols as $symbol) {
            $data = $this->fetchChart($symbol, $startDate, $endDate);
            if ($data) {
                $stockQuotesChart[] = $data;
            }
        }

        foreach ($stockQuotesChart as $k => $quoteChart) {
            foreach ($quoteChart as $dayParams) {
                list($date, $open, $high, $low, $close, $volume, $adjClouse) = array_values($dayParams);

                if (!isset($portfolioChart[$date])) {
                    $portfolioChart[$date] = $dayParams;
                    continue;
                }

                $portfolioChart[$date][1] = (float) $portfolioChart[$date][1] + (float) $open;
                $portfolioChart[$date][2] = (float) $portfolioChart[$date][2] + (float) $high;
                $portfolioChart[$date][3] = (float) $portfolioChart[$date][3] + (float) $low;
                $portfolioChart[$date][4] = (float) $portfolioChart[$date][4] + (float) $close;
                $portfolioChart[$date][5] = (int) $portfolioChart[$date][5] + (int) $volume;
                $portfolioChart[$date][6] = (float) $portfolioChart[$date][6] + (float) $adjClouse;
            }
        }

        return array_values($portfolioChart);
    }

    /**
     * Fetch Stock Quotes.
     *
     * http://download.finance.yahoo.com/d/quotes.csv?s={symbols}&f={keys}.
     *
     * @param array $symbols
     * @param array $aliases
     *
     * @return array
     */
    public function fetchQuotes(array $symbols, array $aliases): array
    {
        $keys = '';
        foreach ($aliases as $key => $alias) {
            if (!isset($this->columnAliases[$alias])) {
                unset($aliases[$key]);
                continue;
            }
            $keys .= $this->columnAliases[$alias];
        }

        if ($keys) {
            $resource = sprintf(
                "http://download.finance.yahoo.com/d/quotes.csv?s=%s&f=$keys",
                implode(',', $symbols)
            );

            $data = $this->curl($resource, 'application/octet-stream');

            return $data ? $this->parse(preg_split('/\r\n|\r|\n/', trim($data)), $aliases) : [];
        }

        return [];
    }

    /**
     * Replace default column aliases, with existing keys and new column aliases.
     *
     * @param array $columnAliases
     *
     * @return YahooFinanceAPI
     */
    public function replaceAliases(array $columnAliases): YahooFinanceAPI
    {
        $aliases = array_flip($this->columnAliases);

        foreach ($columnAliases as $key => $alias) {
            if (isset($aliases[$key])) {
                $aliases[$key] = $alias;
            }
        }
        $this->columnAliases = array_flip($aliases);

        return $this;
    }

    private function parse(array $data, array $aliases = null): array
    {
        if ($aliases) {
            array_walk($data, function (&$item, $key, $aliases) {
                $item = array_combine($aliases, str_getcsv($item));
            }, $aliases);
            return $data;
        }
        array_walk($data, function (&$item) {
            $item = str_getcsv($item);
        });
        return $data;
    }

    private function curl($url, $type = 'text/csv')
    {
        $options = [
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_CONNECTTIMEOUT => 1,
            CURLOPT_USERAGENT => 'YahooFinanceApiWebKit',
        ];

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $data = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        curl_close($ch);

        return  strpos($contentType, $type) !== false && $httpCode === 200 ? $data : false;
    }
}
