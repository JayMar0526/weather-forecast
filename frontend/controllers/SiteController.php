<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use Cmfcmf\OpenWeatherMap;
use Http\Factory\Guzzle\RequestFactory;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use Cmfcmf\OpenWeatherMap\Exception as OWMException;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /***
     * 
     */
    public function actionResult()
    {
        if (Yii::$app->request->isAjax) {
            // Fetch data from request;
            $result = Yii::$app->request->queryParams;
            $postalCode = $result['postalC'];

            // Initialize Request Factory;
            $httpRequestFactory = new RequestFactory();
            $httpClient = GuzzleAdapter::createWithConfig([]);
            $owm = new OpenWeatherMap('2f8796eefe67558dc205b09dd336d022', $httpClient, $httpRequestFactory);
            
            // Weather Variables;
            $units = 'metric';
            $lang = 'en';
            $days = 3;

            // Current Weather
            $currentWeather = $owm->getWeather($postalCode, $units, $lang);
            $lat = $currentWeather->city->lat;
            $lon = $currentWeather->city->lon;
            
            // UV Index
            $forecastuvIndex = $owm->getForecastUVIndex($lat, $lon);
            
            // Daily Forecast
            $dailyForecasts = $owm->getDailyWeatherForecast($postalCode, $units, $lang, '', $days);

            // Weather Map
            $weatherMap = "https://tile.openweathermap.org/map/temp_new/3/3/3.png?appid=05cf8d47898ef7d16cd26b29d2b5d73b";
        }

        return $this->renderAjax('weather-result', [    
            'currentWeather' => $currentWeather,
            'dailyForecasts' => $dailyForecasts,
            'weatherMap' => $weatherMap,
            'forecastuvIndex' => $forecastuvIndex,
        ]);
    }

}
