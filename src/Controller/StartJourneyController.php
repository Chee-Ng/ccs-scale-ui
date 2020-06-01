<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpClient\HttpClient;
use App\Models\GuideMatchJourneyModel;
use App\GuideMatchApi\GuideMatchJourneyApi;

class StartJourneyController extends AbstractController
{
    public function startJourney(Request $request, $journeyUuid)
    {
        $searchBy = $request->query->get('q');

        $httpClient = HttpClient::create();
        $api = new GuideMatchJourneyApi($httpClient, getenv('GUIDE_MATCH_DECISION_TREE_API'));
       
        $model = new GuideMatchJourneyModel($api);
        $model->startJourney($journeyUuid, $searchBy);
        
        return $this->render('pages/guide_match_questions.html.twig', [
            'journeyInstanceId' => $model->getJourneyInstanceId(),
            'journeyId' => $journeyUuid,
            'definedAnswers' => $model->getDefinedAnswers(),
            'uuid' => $model->getUuid(),
            'text' => $model->getText(),
            'type' => $model->getType(),
            'hint' => $model->getHint(),
            'searchBy' => $searchBy,
            'answers' => [],
            'gPage' => 1,
            'lastPage' => 0
        ]);
    }
}
