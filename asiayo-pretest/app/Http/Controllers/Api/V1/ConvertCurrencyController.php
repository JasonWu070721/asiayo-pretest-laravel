<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConvertCurrencyRequest;
use Exception;

class ConvertCurrencyController extends Controller
{

    private $max_amount = 100000000;
    private $currencies = array(
        'TWD' => [
            'TWD' => 1,
            'JPY' => 3.669,
            'USD' => 0.03281
        ], 'JPY' => [
            'TWD' => 0.26956,
            'JPY' => 1,
            'USD' => 0.00885
        ], 'USD' => [
            'TWD' => 30.444,
            'JPY' => 111.801,
            'USD' => 1
        ]
    );


    /**
     * Converts a currency amount represented as a formatted string with a dollar sign and comma separators into its numeric value.
     *
     * @param string $input_currency_amount The formatted currency amount string.
     * @return string The numeric value of the currency amount.
     * 
     * @example
     * $input_string = '$1,000.50';
     * $result = currency_amount_to_string_number($input_currency_amount);
     * // $result should be '1000.50'
     */
    public function currency_amount_to_string_number($input_currency_amount)
    {
        $amount = str_replace('$', '', $input_currency_amount);
        return str_replace(',', '', $amount);
    }

    /**
     * Convert the input string to a formatted currency amount string.
     *
     * @param string $input_string The numeric string to convert, can include digits and a decimal point.
     * @return string The converted currency amount string with appropriate thousands separator and currency symbol.
     *
     * @example
     * $input_string = '12345.67';
     * $result = string_number_to_currency_amount($input_string);
     * // $result should be '$12,345.67'
     *
     */

    public function string_number_to_currency_amount($input_string)
    {
        return '$' . number_format($input_string, 2);
    }


    /**
     * Convert an amount from one currency to another using an exchange rate.
     *
     * This function takes an input amount and converts it to an equivalent amount
     * in another currency based on the provided exchange rate.
     *
     * @param string $exchange_rate The exchange rate to use for conversion (target_currency/source_currency).
     * @param string $amount The amount to convert.
     * @return float The converted amount in the target currency.
     *
     * @example
     * $exchange_rate = '0.85'; // 1 USD = 0.85 EUR
     * $amount = '100.00';
     * $result = convert_currency($exchange_rate, $amount);
     * // If successful, $result should be approximately 85.00
     *
     */
    public function convert_currency($exchange_rate, $amount)
    {
        return floatval(round(bcmul($exchange_rate, $amount, 4), 2));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(ConvertCurrencyRequest $request)
    {

        $request_filter = $request->only(
            'source',
            'target',
            'amount',
        );

        try {

            $amount = $this->currency_amount_to_string_number($request_filter['amount']);

            if (floatval($amount) > $this->max_amount) {
                $details = 'The provided amount is too large. Please enter a valid amount.';

                return response()->json([
                    'status' => 'error',
                    'msg' => 'Invalid Input.',
                    'details' => $details
                ], 422);
            }

            $source = strtoupper($request_filter['source']);
            $target = strtoupper($request_filter['target']);

            if (empty($this->currencies[$source][$target])) {
                $details = 'An error occurred while executing the database query. Please review your input and try again.';

                return response()->json([
                    'status' => 'error',
                    'msg' => 'Invalid Input.',
                    'details' => $details
                ], 422);
            };


            $currency_amount = $this->convert_currency($this->currencies[$source][$target], $amount);

            $currency_amount = $this->string_number_to_currency_amount($currency_amount);

            return response()->json([
                'msg' => 'success',
                'amount' => $currency_amount
            ], 200);
        } catch (Exception $e) {
            $details = 'An unexpected error occurred while processing your request.';

            return response()->json(
                [
                    'status' => 'error',
                    'msg' => 'Internal Server Error.',
                    'details' => $details,
                ],
                500
            );
        }
    }
}
