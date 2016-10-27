Yahoo Finance API Bundle
========================

![](https://github.com/atlance/yahoo-finance-api/blob/master/demo.gif)
----------------------------------------------------------------------
**Important**: _This Bundle does not work with **~~YQL~~**! This Bundle works with **CSV**_.

**_- Why not ~~YQL~~?_.**
Look at it: https://developer.yahoo.com/forums/#/categories/yql

---

####Installation:

1. Download YahooFinanceApiBundle using composer
2. Enable the Bundle
3. Import YahooFinanceApiBundle routing

**Step 1: Download YahooFinanceApiBundle using composer**

 ```composer require atlance/yahoo-finance-api```


**Step 2: Enable the bundle**

Enable the bundle in the kernel:

```
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new YahooFinanceApiBundle\YahooFinanceApiBundle(),
        // ...
    );
}
```

**Step 3: Import YahooFinanceApiBundle routing**

Now that you have activated the bundle, all is left to do is import the YahooFinanceApiBundle routing files.

By importing the routing files you will have ready the path for search, to collect historical data of stock quotes,.. etc.

```
# app/config/routing.yml

yahoo_finance_api:
  resource: "@YahooFinanceApiBundle/Controller/"
  type:     annotation
```



###How to use it ?

Now that you have completed the installation. Run the following command:
`php bin/console debug:router`

```
 -------------------------------------- ---------- -------- ------ -----------------------------------------
  Name                                   Method     Scheme   Host   Path
 -------------------------------------- ---------- -------- ------ -----------------------------------------
  api_yahoo_finance_search               GET        ANY      ANY    /api/yahoo-finance/search/{query}
  api_yahoo_finance_chart_quote          POST       ANY      ANY    /api/yahoo-finance/chart/quote
  api_yahoo_finance_chart_portfolio      POST       ANY      ANY    /api/yahoo-finance/chart/portfolio
```
###api_yahoo_finance_search

Suppose you have a form to create stock quotes, but you have no idea which symbols have stock quotes or the 'correct' name ... What to do?

Solutions:

1. Go to http://finance.yahoo.com/, search these data.

2. Request (method GET and only Ajax) `/api/yahoo-finance/search/{query}`, will returned the array in JSON.

_Recommend: Solution #2 + [jQuery UI Autocomplete Widget](https://api.jqueryui.com/autocomplete/)_
_Example_:
```
$inputSearch = $('.search-input');

$inputSearch.autocomplete({
  source: function (request, response) {
    var callback = function (res) {
      var suggestions = [];
      $.each(res, function (i, val) {
        // set property for autocomplete widget
        val.label = val.name + ' | ' + val.symbol + ' | ' + val.typeDisp + ' - ' + val.exchDisp;
        val.value = val.name;
        suggestions.push(val);
      });

      response(suggestions);
    };

    $.ajax({
      type: 'GET',
      url: '/api/yahoo-finance/search/' + request.term,
      dataType: 'json',
      success: function (res) {
        callback(res);
      },
    });
  },

  select: function (event, ui) {
   //...
  },

  minLength: 2,
});
```

###api_yahoo_finance_chart_quote

Now we have a stock symbol(ticket) etc. We want to create Yahoo Finance charts ...

Request (method POST and only Ajax) `/api/yahoo-finance/chart/quote` where request data: symbol, startDate, endDate;

_Example_:
```
var symbol = 'AAPL';

$.ajax({
    type: 'POST',
    url: '/api/yahoo-finance/chart/quote',
    data: JSON.stringify({ symbol: symbol, startDate: '-2 week', endDate: 'now' }),
    dataType: 'json',
    success: function (dataChart) {
      // render data via js lib (Google Charts or D3.js chart library etc.)
    },
});
```


###api_yahoo_finance_chart_portfolio

Same as **api_yahoo_finance_chart_quote**, only where `symbol` - `symbols`(symbols as array)

and `url: /api/yahoo-finance/chart/portfolio`.

---

##Yahoo Finance API Bundle as service.

Service - used as a service. ;)

Examples:

* in controller:
```$api = $this->get('yahoo_finance_api');```

* in your service.yml as DI service


```
app.best_your_service:
    class: AppBundle\Service\BestYourService
    calls:
        [setApi, ['@yahoo_finance_api']]
```


used as you want ...

This service have four public methods:

**1**.`function fetchChart(string $symbol, \DateTime $startDate, \DateTime $endDate): array`

Example: `$this->get('yahoo_finance_api')->fetchChart('AAPL', new \DateTime('-3 month'), new \DateTime());`

**2**.`function fetchPortfolioChart(array $symbols, \DateTime $startDate, \DateTime $endDate, bool $timestamp = true): array`

Example:
```
$this->get('yahoo_finance_api')
->fetchPortfolioChart(['AAPL', 'MSFT'], new \DateTime('-3 month'), new \DateTime());
```
**3**.`function fetchQuotes(array $symbols, array $aliases): array`
\- _Aliases? Hmmm ... wtf?!_
Don't worry, it's probably the hardest thing in this service.

___
The fact that the ... yahoo finance quotes has about 90 parameters, which looks like that:
 `a` – ask, `b` – bid, `b2` – ask (realtime), `b3` – bid (realtime), `p` – previous close, `o` – open, `y` – dividend yield, `d` – dividend per share, `r1` – dividend pay date, `q` – ex-dividend date, `c1` – change, `c` – change & percentage change, ...

 \- _Do I need to remember these settings?_
 \- No. I created the aliases. And you can rewrite them. Easy.
 Example:
```
 public function setApi(YahooFinanceAPI $api)
 {
     $this->api = $api->replaceAliases([
         'n' => 'companyName',
         'x' => 'stockExchangeCode'
     ]);
 }
```
 or
 ```
 $api = $this->get('yahoo_finance_api')->replaceAliases(['n' => 'companyName', 'x' => 'stockExchangeCode'])
 ```
 or (_best practices_), in `service.yml`:
```
 parameters:
   yahoo_finance_api.aliases:
     -
       n: 'companyName'
       x: 'stockExchangeCode'

 services:
   app.best_your_service:
     class: AppBundle\Service\BestYourService
     factory: ["@yahoo_finance_api", replaceAliases]
     arguments: ["%yahoo_finance_api.aliases%"]
```
 and use:

 `$api->fetchQuotes(['AAPL', 'MSFT'], ['companyName', 'stockExchangeCode, *and other data you want*'])`

**4**.`function replaceAliases(array $columnAliases): YahooFinanceAPI`

---

**Important**: _All keys and aliases, see annotation to the service and here_:

| `Key` | Description | Default `alias`  |
| ------------- |:-------------|:-----:|
| ***a*** | Ask | `Ask` |
| ***a2*** | Average Daily Volume | `AverageDailyVolume` |
| ***a5*** | Ask Size | `AskSize` |
| ***b*** | Bid | `Bid` |
| ***b2*** | Ask (Real-time) | `AskRealTime` |
| ***b3*** | Bid (Real-time) | `BidRealTime` |
| ***b4*** | Book Value | `BookValue` |
| ***b6*** | Bid Size | `BidSize` |
| ***c*** | Change & Percent Change | `ChangePercentChange` |
| ***c1*** | Change | `Change` |
| ***c3*** | Commission | `Commission` |
| ***c6*** | Change (Real-time) | `ChangeRealTime` |
| ***c8*** | After Hours Change (Real-time) | `AfterHoursChange` |
| ***d*** | Dividend/Share | `DividendShare` |
| ***d1*** | Last Trade Date | `LastTradeDate` |
| ***d2*** | Trade Date | `TradeDate` |
| ***e*** | Earnings/Share | `EarningsShare` |
| ***e1*** | Error Indication (returned for symbol changed / invalid) | `ErrorIndication` |
| ***e7*** | EPS Estimate Current Year | `EPSEstimateCurrentYear` |
| ***e8*** | EPS Estimate Next Year | `EPSEstimateNextYear` |
| ***e9*** | EPS Estimate Next Quarter | `EPSEstimateNextQuarter` |
| ***f6*** | Float Shares | `FloatShares` |
| ***g*** | Day’s Low | `DaysLow` |
| ***h*** | Day’s High | `DaysHigh` |
| ***j*** | 52-week Low | `52weekLow` |
| ***k*** | 52-week High | `52weekHigh` |
| ***g1*** | Holdings Gain Percent | `HoldingsGainPercent` |
| ***g3*** | Annualized Gain | `AnnualizedGain` |
| ***g4*** | Holdings Gain | `HoldingsGain` |
| ***g5*** | Holdings Gain Percent (Real-time) | `HoldingsGainPercentRealTime` |
| ***g6*** | Holdings Gain (Real-time) | `HoldingsGainRealTime` |
| ***i*** | More Info | `MoreInfo` |
| ***i5*** | Order Book (Real-time) | `OrderBook` |
| ***j1*** | Market Capitalization | `MarketCapitalization` |
| ***j3*** | Market Cap (Real-time) | `MarketCap` |
| ***j4*** | EBITDA | `EBITDA` |
| ***j5*** | Change From 52-week Low | `ChangeFrom52weekLow` |
| ***j6*** | Percent Change From 52-week Low | `PercentChangeFrom52weekLow` |
| ***k1*** | Last Trade (Real-time) With Time | `LastTradeRealTimeWithTime` |
| ***k2*** | Change Percent (Real-time) | `LastTradeRealTimeWithTime` |
| ***k3*** | Last Trade Size | `LastTradeSize` |
| ***k4*** | Change From 52-week High | `ChangeFrom52weekHigh` |
| ***k5*** | Percebt Change From 52-week High | `PercentChangeFrom52weekHigh` |
| ***l*** | Last Trade (With Time) | `LastTradeWithTime` |
| ***l1*** | Last Trade (Price Only) | `LastTradePriceOnly` |
| ***l2*** | High Limit | `HighLimit` |
| ***l3*** | Low Limit | `LowLimit` |
| ***m*** | Day’s Range | `DaysRange` |
| ***m2*** | Day’s Range (Real-time) | `DaysRangeRealTime` |
| ***m3*** | 50-day Moving Average | `50dayMovingAverage` |
| ***m4*** | 200-day Moving Average | `200dayMovingAverage` |
| ***m5*** | Change From 200-day Moving Average | `ChangeFrom200dayMovingAverage` |
| ***m6*** | Percent Change From 200-day Moving Average | `PercentChangeFrom200dayMovingAverage` |
| ***m7*** | Change From 50-day Moving Average | `ChangeFrom50dayMovingAverage` |
| ***m8*** | Percent Change From 50-day Moving Average | `PercentChangeFrom50dayMovingAverage` |
| ***n*** | Name | `Name` |
| ***n4*** | Notes | `Notes` |
| ***o*** | Open | `Open` |
| ***p*** | Previous Close | `PreviousClose` |
| ***p1*** | Price Paid | `PricePaid` |
| ***p2*** | Change in Percent | `ChangeInPercent` |
| ***p5*** | Price/Sales | `PriceSales` |
| ***p6*** | Price/Book | `PriceBook` |
| ***q*** | Ex-Dividend Date | `ExDividendDate` |
| ***r*** | P/E Ratio | `PERatio` |
| ***r1*** | Dividend Pay Date | `DividendPayDate` |
| ***r2*** | P/E Ratio (Real-time) | `PERatioRealTime` |
| ***r5*** | PEG Ratio | `PEGRatio` |
| ***r6*** | Price/EPS Estimate Current Year | `PriceEPSEstimateCurrentYear` |
| ***r7*** | Price/EPS Estimate Next Year | `PriceEPSEstimateNextYear` |
| ***s*** | Symbol | `Symbol` |
| ***s1*** | Shares Owned | `SharesOwned` |
| ***s7*** | Short Ratio | `ShortRatio` |
| ***t1*** | Last Trade Time | `LastTradeTime` |
| ***t6*** | Trade Links | `TradeLinks` |
| ***t7*** | Ticker Trend | `TickerTrend` |
| ***t8*** | 1 yr Target Price | `1yrTargetPrice` |
| ***v*** | Volume | `Volume` |
| ***v1*** | Holdings Value | `HoldingsValue` |
| ***v7*** | Holdings Value (Real-time) | `HoldingsValueRealTime` |
| ***w*** | 52-week Range | `52weekRange` |
| ***w1*** | Day’s Value Change | `DaysValueChange` |
| ***w4*** | Day’s Value Change (Real-time) | `DaysValueChangeRealTime` |
| ***x*** | Stock Exchange | `StockExchange` |
| ***y*** | Dividend Yield | `DividendYield` |

P.S. Maybe it's not all there is. To be continued ...