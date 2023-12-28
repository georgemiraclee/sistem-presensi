<?php
/**
 * This file is part of the Payroll Calculator Package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author         Steeve Andrian Salim
 * @copyright      Copyright (c) Steeve Andrian Salim
 */

// ------------------------------------------------------------------------

include_once APPPATH."libraries/PPH21/spl/DataStructures/SplArrayObject.php";
include_once APPPATH."libraries/PPH21/Taxes/AbstractPph.php";
/**
 * Class Pph21
 * @package Steevenz\IndonesiaPayrollCalculator\Taxes
 */
class Pph21 extends AbstractPph
{
    /**
     * PPh21::calculate
     *
     * @return \O2System\Spl\DataStructures\SplArrayObject
     */
    public function calculate()
    {
        /**
         * PPh21 dikenakan bagi yang memiliki penghasilan lebih dari 4500000
         */
        if($this->calculator->result->earnings->nett > 4500000) {
            // Annual PTKP base on number of dependents family
            $this->result->ptkp->amount = $this->calculator->provisions->state->getPtkpAmount($this->calculator->employee->numOfDependentsFamily, $this->calculator->employee->maritalStatus)/12;
            
            // Annual PKP (Pajak Atas Upah)
            if($this->calculator->employee->earnings->holidayAllowance > 0 && $this->calculator->employee->bonus->getSum() == 0) {
                // Pajak Atas Upah
                $earningTax = ($this->calculator->result->earnings->annualy->nett - $this->result->ptkp->amount) * ($this->getRate($this->calculator->result->earnings->nett) / 100);
                
                // Penghasilan + THR Kena Pajak
                $this->result->pkp = ($this->calculator->result->earnings->annualy->nett + $this->calculator->employee->earnings->holidayAllowance) - $this->result->ptkp->amount;

                $this->result->liability->annual = $this->result->pkp - $earningTax;
            } elseif($this->calculator->employee->earnings->holidayAllowance > 0 && $this->calculator->employee->bonus->getSum() > 0) {
                // Pajak Atas Upah
                $earningTax = ($this->calculator->result->earnings->annualy->nett - $this->result->ptkp->amount) * $this->getRate($this->calculator->result->earnings->nett);
                
                // Penghasilan + THR Kena Pajak
                $this->result->pkp = ($this->calculator->result->earnings->annualy->nett + $this->calculator->employee->earnings->holidayAllowance + $this->calculator->employee->bonus->getSum()) - $this->result->ptkp->amount;

                $this->result->liability->annual = $this->result->pkp - $earningTax;
            } else {
                if ($this->calculator->result->earnings->annualy->nett/12>$this->result->ptkp->amount) {
                    $this->result->pkp = $this->pembulatan(($this->calculator->result->earnings->annualy->nett/12) - $this->result->ptkp->amount);
                }else{
                    $this->result->pkp = 0;
                }
                $this->result->liability->annual = $this->result->pkp * $this->getRate($this->calculator->result->earnings->nett);
            }

            // Jika tidak memiliki NPWP dikenakan tambahan 20%
            if($this->calculator->employee->hasNPWP === false) {
                $this->result->liability->annual = $this->result->liability->annual + ($this->result->pkp * (20/100));
            }
            $this->result->liability->monthly = $this->result->liability->annual / 12;

            // Perhitungan lapisan pajak
            if ($this->result->pkp > 50000000) {
                $lapisan1 = 50000000*0.05;
            }else{
                $lapisan1 = $this->result->pkp*0.05;
            }

            if ($this->result->pkp > 250000000) {
                $lapisan2 = 250000000*0.15;
            }else{
                if ($this->result->pkp > 50000000) {
                    $lapisan2 = ($this->result->pkp-50000000)*0.15;
                }else{
                    $lapisan2 = 0;
                }
            }
            $this->result->pph21count = $lapisan1+$lapisan2;
        }else{
            $this->result->pph21count = 0;
        }
        
        return $this->result;
    }

    public function pembulatan($uang)
    {
        $ratusan = substr($uang, -3);
        $akhir = $uang - ($ratusan);
        return $akhir;
    }
}