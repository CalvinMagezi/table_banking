<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Email: robisignals@gmail.com
 * Date: 07/09/2019
 * Time: 14:07
 */

namespace App\Traits;


trait Amortization
{
   // private $loanAmount;
    private $term_years;
   // private $interest;
    private $terms;
    private $period;
    private $currency = "XXX";
    private $principal;
    private $balance;
    private $term_pay;

    private $loanAmount, $totalPayments, $interest;

    /**
     * Amortization constructor.
     * @param $data
     */
    public function __construct($data)
    {
        if($this->validate($data)) {

            $this->loanAmount 	= (float) $data['loanAmount'];
            $this->term_years 	= (int) $data['term_years'];
            $this->interest 	= (float) $data['interest'];
            $this->terms 		= (int) $data['terms'];

            $this->terms = ($this->terms == 0) ? 1 : $this->terms;
            $this->period = $this->terms * $this->term_years;
            $this->interest = ($this->interest/100) / $this->terms;
            $results = array(
                'inputs' => $data,
                'summary' => $this->getSummary(),
                'shedule' => $this->getShedule(),
            );
            $this->getJSON($results);
        }
    }

    public function calculatePayment(float $loanAmount, int $totalPayments, float $interest)
    {
        $value1 = $interest * pow((1 + $interest), $totalPayments);
        $value2 = pow((1 + $interest), $totalPayments) - 1;
        $pmt    = $loanAmount * ($value1 / $value2);

        return $pmt;
    }

    public function calc_principal($number, $rate, $payment){
    }

    public function calc_number($principal , $rate, $payment) {
    }

    public function calc_rate($principal, $number, $payment) {
    }

   /* public function calc_payment($principal, $number, $rate, 2) {
    }*/

    public function calc_payment($pv, $payno, $int, $accuracy) {

    }

    public function print_schedule($balance, $rate, $payment) {

    }

    /**
     * @param $data
     * @return bool
     */
    private function validate($data) {
        $data_format = array(
            'loanAmount' 	=> 0,
            'term_years' 	=> 0,
            'interest' 		=> 0,
            'terms' 		=> 0
        );
        $validate_data = array_diff_key($data_format,$data);

        if(empty($validate_data)) {
            return true;
        }else{
            echo "<div style='background-color:#ccc;padding:0.5em;'>";
            echo '<p style="color:red;margin:0.5em 0em;font-weight:bold;background-color:#fff;padding:0.2em;">Missing Values</p>';
            foreach ($validate_data as $key => $value) {
                echo ":: Value <b>$key</b> is missing.<br>";
            }
            echo "</div>";
            return false;
        }
    }

    /**
     * @return array
     */
    private function calculate()
    {
        $deno = 1 - 1 / pow((1+ $this->interest),$this->period);
        $this->term_pay = ($this->loanAmount * $this->interest) / $deno;
        $interest = $this->loanAmount * $this->interest;
        $this->principal = $this->term_pay - $interest;
        $this->balance = $this->loanAmount - $this->principal;
        return array (
            'payment' 	=> $this->term_pay,
            'interest' 	=> $interest,
            'principal' => $this->principal,
            'balance' 	=> $this->balance
        );
    }

    /**
     * @return array
     */
    public function getSummary()
    {
        $this->calculate();
        $total_pay = $this->term_pay *  $this->period;
        $total_interest = $total_pay - $this->loanAmount;
        return array (
            'total_pay' => $total_pay,
            'total_interest' => $total_interest,
        );
    }

    /**
     * @return array
     */
    public function getShedule ()
    {
        $shedule = array();

        while  ($this->balance >= 0) {
            array_push($shedule, $this->calculate());
            $this->loanAmount = $this->balance;
            $this->period--;
        }
        return $shedule;
    }

    /**
     * @param $data
     */
    private function getJSON($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }

}