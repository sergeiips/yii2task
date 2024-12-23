<?php

namespace app\controllers;

use app\models\Loan;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;

class RestController extends Controller
{
    /**
     * Request action.
     */
    public function actionRequests()
    {
        $dataLoan = Yii::$app->request->post();

        if (isset($dataLoan['user_id']) && !(Loan::findOne(['userId' => $dataLoan['user_id'], 'status' => Loan::LOAN_STATUS_APPROVED]))) {
            try {
                $loan = new Loan();
                $loan->amount = $dataLoan['amount'];
                $loan->userId = $dataLoan['user_id'];
                $loan->term = $dataLoan['term'];
                $loan->status = Loan::LOAN_STATUS_NEW;
                $loan->save();
            } catch (\TypeError $e) {
                return $this->asJson(['result' => false, 'message' => 'Validation type error']);
            }

            return $this->asJson(
                ['result' => false, 'id' => $loan->getPrimaryKey()]
            );
        }

        return $this->asJson(['result' => false, 'message' => 'Already approved for given user']);
    }

    /**
     * Process all requests action.
     */
    public function actionProcessor()
    {
        $delay = Yii::$app->request->get('delay') ?? 0;

        while (true) {
            $unprocessedLoan = Loan::findOne(['status' => Loan::LOAN_STATUS_NEW]);

            if (!$unprocessedLoan instanceof Loan) {
                break;
            }

            $isCurrUserHasApprovedLoan = Loan::findOne(
                ['userId' => $unprocessedLoan->userId, 'status' => Loan::LOAN_STATUS_APPROVED]
            );

            if ($isCurrUserHasApprovedLoan) {
                $unprocessedLoan->status = Loan::LOAN_STATUS_DECLINED;
                $unprocessedLoan->save();
                continue;
            }

            $unprocessedLoan->status = Loan::LOAN_STATUS_IN_PROGRESS;
            $unprocessedLoan->save();
            $isApprovedChance = random_int(0,9) === 1;
            sleep($delay);

            $unprocessedLoan->status = $isApprovedChance ? Loan::LOAN_STATUS_APPROVED : Loan::LOAN_STATUS_DECLINED;

            $unprocessedLoan->save();
        }

        return  $this->asJson(['result' => true]);
    }
}
