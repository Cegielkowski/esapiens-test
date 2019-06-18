<?php

namespace App\Libraries;

use App\Models\Transactions;

class Helper
{
    private $tax = 0.1; // 10% tax from system

    public function __construct()
    {
        //
    }

    /**
     * @param int $userId Id from the user
     * @param int $commentId Id from the comment
     * @param int $value Value of the transaction
     *
     *  This function will register the transaction, and apply the tax from the system
     *
     */
    public static function registerTransaction(int $userId, $commentId, int $value)
    {
       $helper = new Helper();
       $tax = $value * $helper->getTax();
       $value -= $tax;

        $attributes = [
            'user_id' => $userId,
            'comment_id' => $commentId,
            'value' => $value
        ];

        $transactions = Transactions::create($attributes);

        $attributesTax = [
            'user_id' => $userId,
            'comment_id' => $commentId,
            'value' => $tax,
            'transaction_id' => $transactions->id
        ];

        $transactionsTax = Transactions::create($attributesTax);

        return [$transactions->id, $transactionsTax->id];
    }


    /**
     * @param int $basicTransaction id from the real transaction
     * @param int $taxTransaction id from the tax transaction
     * @param int $commentId id from the comment
     *
     * This function will put the comment id on the transactions
     */
    public static function updateTransaction(int $basicTransaction, int $taxTransaction, int $commentId)
    {
        $basicTransaction = Transactions::findOrFail($basicTransaction);
        $taxTransaction = Transactions::findOrFail($taxTransaction);

        $basicTransaction->update([
            'comment_id' => $commentId
        ]);

        $taxTransaction->update([
            'comment_id' => $commentId
        ]);
    }

    /**
     * @return float
     */
    private function getTax(): float
    {
        return $this->tax;
    }

    /**
     * @param float $tax
     */
    private function setTax(float $tax)
    {
        $this->tax = $tax;
    }
}
