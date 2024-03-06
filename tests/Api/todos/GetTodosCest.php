<?php


namespace App\Tests\Api;

use App\Factory\TodoFactory;
use App\Tests\Support\ApiTester;
use App\Tests\Support\Step\Api\Anonymous;

class todosCest
{
    public function _before(ApiTester $I)
    {
    }

    /**
     * @param Anonymous $I
     * @return void
     */
    public function tryToGetAllTodosAsAnonymous(Anonymous $I)
    {
        TodoFactory::createMany(30);

        $I->am('an anonymous user');
        $I->wantTo('list all todos');

        $I->haveHttpHeader("accept", "application/ld+json");
        $I->haveHttpHeader("Content-Type", "application/json");
        $I->sendGet('todos');

        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            "@context" => "/api/contexts/Todo",
            "@type" => "hydra:Collection",
            "hydra:totalItems" => 30
        ]);
    }
}
