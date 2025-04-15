Following steps:
- don't forget to change `paymentfee` namespace and package name in `composer.json`
 to your own, as `paymentfee` keyword should not be used anywhere in your task;
- `\paymentfee\CommissionTask\Service\Math` is an example class provided for the skeleton and could or could not be used by your preference;
- needed scripts could be found inside `composer.json`;
- before submitting the task make sure that all the scripts pass (`composer run test` in particular);
- this file should be updated before submitting the task with the documentation on how to run your program.



Code structure should not depend on current concrete configuration. For example, adding new currency should not require changing code itself, like adding new methods or extending switch cases etc.



Commission fee is always calculated in the currency of the operation.
Commission fees are rounded up to currency's decimal places. 0.023EUR => 0.03 EUR.
Deposit rule
    All deposits are charged 0.03% of deposit amount.
Withdraw rules
    There are different calculation rules for withdraw of private and business clients.
Private Clients
    Commission fee - 0.3% from withdrawn amount.
    1000.00 EUR for a week (from Monday to Sunday) is free of charge. Only for the first 3 withdraw operations per a week.
    If total free of charge amount is exceeded them commission is calculated only for the exceeded amount (i.e. up to 1000.00 EUR no commission fee is applied).
    For the second rule you will need to convert operation amount if it's not in Euros. Please use rates provided by https://api.exchangeratesapi.io/latest.
Business Clients
    Commission fee - 0.5% from withdrawn amount.



Planned Schema:
- Static CurrencyConverter
    - static convertTo($amount,$current_currency,$to_currency):array
- Static Importer
    - static readCsv($filename):array (create an integer index)
- BalanceHistory
    - param withdrawn_history [stores dates]
    - param withdrawn_amount
    - param client_type //maybe put in user class?
    - withdraw
    - deposit
- Abstract FeeCalculator
    - static calculate(BalanceHistory $balance): float
    - private meetsDailyWithdrawalCriteria
    - private meetsWithdrawnAmountCriteria
- WithdrawTaxCalculator extends FeeCalculator (put tax conditions inside here)
    - overrides FeeCalculator::calculateTax(BalanceHistory $balance)
- DepositTaxCalculator extends FeeCalculator (put tax conditions inside here)
    - overrides FeeCalculator::calculateTax(BalanceHistory $balance)
- Singleton Display
    - param taxes [array]
    - addToFeeHistory ($action_id, $tax_amount)