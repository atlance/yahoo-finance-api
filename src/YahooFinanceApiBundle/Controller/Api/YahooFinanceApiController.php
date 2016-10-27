<?php

namespace YahooFinanceApiBundle\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/yahoo-finance")
 */
class YahooFinanceApiController extends Controller
{
    /**
     * @Route("/search/{query}", name="api_yahoo_finance_search", condition="request.isXmlHttpRequest()")
     * @Method({"GET"})
     *
     * @param Request $request
     * @param string $query
     *
     * @return JsonResponse
     */
    public function searchAction(Request $request, string $query)
    {
        $data = $this->curl(
            $request,
            "https://s.yimg.com/xb/v6/finance/autocomplete?query=$query&lang=en-US&region=US&corsDomain=finance.yahoo.com"
        );

        if ($data) {
            $data = json_decode($data, true)['ResultSet']['Result'];

            return new JsonResponse($data);
        }

        return new JsonResponse(null, 404);
    }

    /**
     * @Route(
     *     "/chart/quote",
     *     name="api_yahoo_finance_chart_quote",
     *     condition="request.isXmlHttpRequest()"
     * )
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function chartQuoteAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $chart = $this->get('yahoo_finance_api')
            ->fetchChart(
                $data['symbol'],
                new \DateTime($data['startDate']),
                new \DateTime($data['endDate'])
            );

        return  new JsonResponse(json_encode($chart, JSON_NUMERIC_CHECK), 200, [], true);
    }

    /**
     * @Route(
     *     "/chart/portfolio",
     *     name="api_yahoo_finance_chart_portfolio",
     *     condition="request.isXmlHttpRequest()"
     * )
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function chartPortfolioAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $chart = $this->get('yahoo_finance_api')
            ->fetchPortfolioChart(
                $data['symbols'],
                new \DateTime($data['startDate']),
                new \DateTime($data['endDate'])
            );

        return new JsonResponse(json_encode($chart, JSON_NUMERIC_CHECK), 200, [], true);
    }

    private function curl(Request $request, $url, $type = 'json')
    {
        $options = [
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_CONNECTTIMEOUT => 1,
            CURLOPT_USERAGENT => $request->headers->get('User-Agent'),
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
