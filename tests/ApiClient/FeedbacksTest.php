<?php

namespace Dakword\WBSeller\Tests\ApiClient;

use Dakword\WBSeller\API\Endpoint\Feedbacks;
use Dakword\WBSeller\Tests\ApiClient\TestCase;

class FeedbacksTest extends TestCase
{

    private $Feedbacks;
    
    public function setUp(): void
    {
        parent::setUp();
        
        $this->Feedbacks = $this->Feedbacks();
    }

    public function test_Class()
    {
        $this->assertInstanceOf(Feedbacks::class, $this->API()->Feedbacks());
    }

    public function test_hasNew()
    {
        $result = $this->Feedbacks->hasNew();

        $this->assertFalse($result->error);

        if(!$result->error) {
            $this->assertObjectHasAttribute('hasNewQuestions', $result->data);
            $this->assertObjectHasAttribute('hasNewFeedbacks', $result->data);
        }
    }

    public function test_unansweredCount()
    {
        $result = $this->Feedbacks->unansweredCount();
        
        $this->assertFalse($result->error);

        if(!$result->error) {
            $this->assertObjectHasAttribute('data', $result);
            $this->assertObjectHasAttribute('countUnanswered', $result->data);
            $this->assertObjectHasAttribute('countUnansweredToday', $result->data);
            $this->assertObjectHasAttribute('valuation', $result->data);
        }
    }

    public function test_parentSubjects()
    {
        $result = $this->Feedbacks->parentSubjects();

        $this->assertFalse($result->error);

        if(!$result->error) {
            $this->assertObjectHasAttribute('data', $result);
            $this->assertIsArray($result->data);

            $firstSubject = array_shift($result->data);
            if($firstSubject) {
                $this->assertObjectHasAttribute('subjectId', $firstSubject);
                $this->assertObjectHasAttribute('subjectName', $firstSubject);
            } else {
                $this->markTestSkipped('No products in the account');
            }
        }
    }

    public function test_subjectRating()
    {
        $result0 = $this->Feedbacks->parentSubjects();
        $firstSubject = array_shift($result0->data);
        if($firstSubject) {
            $result = $this->Feedbacks->subjectRating($firstSubject->subjectId);

            $this->assertFalse($result->error);

            if(!$result->error) {
                $this->assertObjectHasAttribute('data', $result);
                $this->assertObjectHasAttribute('feedbacksCount', $result->data);
                $this->assertObjectHasAttribute('valuation', $result->data);
            }
        } else {
            $this->markTestSkipped('No products in the account');
        }
    }

    public function test_subjectRatingTop()
    {
        $result0 = $this->Feedbacks->parentSubjects();
        $firstSubject = array_shift($result0->data);
        if($firstSubject) {
            $result = $this->Feedbacks->subjectRatingTop($firstSubject->subjectId);

            $this->assertFalse($result->error);

            if(!$result->error) {
                $this->assertObjectHasAttribute('data', $result);
                $this->assertObjectHasAttribute('productMinRating', $result->data);
                $this->assertObjectHasAttribute('productMaxRating', $result->data);
            }
        } else {
            $this->markTestSkipped('No products in the account');
        }
    }

    public function test_list()
    {
        $result = $this->Feedbacks->list();

        $this->assertFalse($result->error);

        if(!$result->error) {
            $this->assertObjectHasAttribute('data', $result);
            $this->assertObjectHasAttribute('countUnanswered', $result->data);
            $this->assertObjectHasAttribute('countArchive', $result->data);
            $this->assertObjectHasAttribute('feedbacks', $result->data);
            $this->assertIsArray($result->data->feedbacks);
        }
    }

    public function test_archive()
    {
        $result = $this->Feedbacks->archive();

        $this->assertFalse($result->error);

        if(!$result->error) {
            $this->assertObjectHasAttribute('data', $result);
            $this->assertObjectHasAttribute('feedbacks', $result->data);
            $this->assertIsArray($result->data->feedbacks);
        }
    }

    public function test_productRating()
    {
        $result = $this->Feedbacks->productRating(123456);

        $this->assertFalse($result->error);

        if(!$result->error) {
            $this->assertObjectHasAttribute('data', $result);
            $this->assertObjectHasAttribute('feedbacksCount', $result->data);
            $this->assertObjectHasAttribute('valuation', $result->data);
        }
    }

    public function test_xlsReport()
    {
        $result = $this->Feedbacks->xlsReport();
        $this->assertFalse($result->error);

        if(!$result->error) {
            $this->assertObjectHasAttribute('data', $result);
            $this->assertObjectHasAttribute('file', $result->data);
            $this->assertObjectHasAttribute('fileName', $result->data);
            $this->assertObjectHasAttribute('contentType', $result->data);
        }
    }

    public function test_changeViewed()
    {
        $result = $this->Feedbacks->changeViewed('xxl', true);
        $response = $this->Feedbacks->response();

        $this->assertFalse($result);
        $this->assertTrue($response->error);
        $this->assertEquals('Не найден отзыв xxl', $response->errorText);
    }

    public function test_sendAnswer()
    {
        $result = $this->Feedbacks->sendAnswer('xxl', 'OK!');
        $response = $this->Feedbacks->response();

        $this->assertFalse($result);
        $this->assertTrue($response->error);
        $this->assertEquals('Не найден отзыв xxl', $response->errorText);
    }

    public function test_get()
    {
        $result = $this->Feedbacks->list(1, 10, true);

        if(!$result->error) {
            $feedbacks = $result->data->feedbacks;
            if($feedbacks) {
                $feedback = array_shift($feedbacks);
                $result = $this->Feedbacks->get($feedback->id);

                $this->assertEquals($feedback->id, $result->data->id);
            } else {
                $this->markTestSkipped('No feedbacks');
            }
        }
    }

    public function test_count()
    {
        $result = $this->Feedbacks->count();
        
        $this->assertFalse($result->error);
        if(!$result->error) {
            $this->assertObjectHasAttribute('data', $result);
        }
    }

    public function test_ratesList()
    {
        $result = $this->Feedbacks->ratesList();

        $this->assertFalse($result->error);
        if(!$result->error) {
            $this->assertObjectHasAttribute('feedbackValuations', $result->data);
            $this->assertObjectHasAttribute('productValuations', $result->data);
        }
    }

    public function test_rateFeedback()
    {
        $result = $this->Feedbacks->rateFeedback('a2X3e4wB-uQDHp63D36M', 1);
        $this->assertFalse($result);
    }

    public function test_rateProduct()
    {
        $result = $this->Feedbacks->rateProduct('a2X3e4wB-uQDHp63D36M', 1);
        $this->assertFalse($result);
    }

}

