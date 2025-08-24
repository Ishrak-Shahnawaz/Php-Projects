<?php
class BankAccount {
    private string $accountNumber;
    private string $accountHolderName;
    private float $balance;
    private string $accountType;
    private array $transactionHistory = [];

    public function __construct($accountNumber, $accountHolderName, $initialBalance, $accountType) {
        $this->accountNumber = $accountNumber;
        $this->accountHolderName = $accountHolderName;
        $this->balance = $initialBalance;
        $this->accountType = $accountType;
    }

    public function deposit($amount) {
        $workDone = false;

        if ($amount > 0) {
            $this->balance = $this->balance + $amount;
            $this->transactionHistory[] = ["type" => "Deposit", "amount" => $amount];
            $workDone = true;
        }

        if (!$workDone) {
            echo "Transaction failed: Invalid deposit amount.";
        } else {
            echo "Transaction success: Deposited $amount.";
        }
    }

    public function withdraw($amount) {
        $workDone = false;

        if ($amount > 0 && $this->balance >= $amount) {
            $this->balance = $this->balance - $amount;
            $this->transactionHistory[] = ["type" => "Withdrawal", "amount" => $amount];
            $workDone = true;
        }

        if (!$workDone) {
            echo "Transaction failed: Insufficient funds or invalid withdrawal amount.";
        } else {
            echo "Transaction success: Withdrew $amount.";
        }
    }

    public function checkBalance() {
        echo "Your current balance is " . $this->balance . ".";
    }

    public function getAccountInfo() {
        echo "Account Number: {$this->accountNumber}";
        echo "Account Holder: {$this->accountHolderName}";
        echo "Account Type: {$this->accountType}";
        echo "Balance: {$this->balance}";
    }

    public function getTransactionHistory() {
        echo "Transaction History:";
        foreach ($this->transactionHistory as $transaction) {
            echo "{$transaction['type']} of amount {$transaction['amount']}";
        }
    }
}

$account1 = new BankAccount("ACC001", "John Doe", 1000.50, "Savings");
$account2 = new BankAccount("ACC002", "Jane Smith", 500.00, "Checking");

$account1->deposit(200);
$account1->withdraw(350);
$account1->checkBalance();
$account1->getTransactionHistory();

$account2->deposit(300);
$account2->withdraw(150);
$account2->checkBalance();
$account2->getTransactionHistory();

$accountNumber1 = "ACC003";
$holderName1 = "Alice Johnson";
$initialBalance1 = 1500.75;
$accountType1 = "Savings";

$accountNumber2 = "ACC004";
$holderName2 = "Bob Wilson";
$initialBalance2 = 800.00;
$accountType2 = "Checking";

$account3 = new BankAccount($accountNumber1, $holderName1, $initialBalance1, $accountType1);
$account4 = new BankAccount($accountNumber2, $holderName2, $initialBalance2, $accountType2);

$depositAmounts = [200.50, 150.00, 75.25];
foreach ($depositAmounts as $amount) {
    $account3->deposit($amount);
}

$withdrawAmounts = [100.00, 50.00, 300.00];
foreach ($withdrawAmounts as $amount) {
    $account4->withdraw($amount);
}

$account3->checkBalance();
$account3->getTransactionHistory();

$account4->checkBalance();
$account4->getTransactionHistory();

?>
