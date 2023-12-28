<?php
include_once("spl/DataStructures/SplArrayObject.php");
include_once("DataStructures/Employee.php");
include_once("DataStructures/Provisions.php");
include_once("Taxes/Pph21.php");

class PayrollCalculator
{
    /**
     * PayrollCalculator::NETT_CALCULATION
     *
     * PPh 21 ditanggung oleh perusahaan atau penyedia kerja.
     *
     * @var string
     */
    const NETT_CALCULATION = 'NETT';

    /**
     * PayrollCalculator::GROSS_CALCULATION
     *
     * PPh 21 ditanggung oleh pekerja/karyawan.
     *
     * @var string
     */
    const GROSS_CALCULATION = 'GROSS';

    /**
     * PayrollCalculator::GROSS_UP_CALCULATION
     *
     * Tanggungan PPh 21 ditambahkan sebagai tunjangan pekerja/karyawan.
     *
     * @var string
     */
    const GROSS_UP_CALCULATION = 'GROSSUP';

    /**
     * PayrollCalculator::$provisions
     *
     * @var \Steevenz\IndonesiaPayrollCalculator\DataStructures\Provisions
     */
    public $provisions;

    /**
     * PayrollCalculator::$employee
     *
     * @var \Steevenz\IndonesiaPayrollCalculator\DataStructures\Employee
     */
    public $employee;

    /**
     * PayrollCalculator::$taxNumber
     * 
     * @var int 
     */
    public $taxNumber = 21;

    /**
     * PayrollCalculator::$method
     *
     * @var string
     */
    public $method = 'NETTO';

    /**
     * PayrollCalculator::$result
     *
     * @var SplArrayObject
     */
    public $result;

    // ------------------------------------------------------------------------

    /**
     * PayrollCalculator::__construct
     *
     * @param array $data
     */
    public function __construct()
    {
        $this->provisions = new Provisions();
        $this->employee = new Employee();
        $this->result = new SplArrayObject([
            'earnings' => new SplArrayObject([
                'base' => 0,
                'fixedAllowance' => 0,
                'annualy' => new SplArrayObject([
                    'nett' => 0,
                    'gross' => 0
                ])
            ]),
            'takeHomePay' => 0
        ]);
    }

    // ------------------------------------------------------------------------
    
    /**
     * PayrollCalculator::getCalculation
     *
     * @return \O2System\Spl\DataStructures\SplArrayObject
     */
    public function getCalculation()
    {
        if($this->taxNumber == 21) {
            return $this->calculateBaseOnPph21();
        } elseif($this->taxNumber == 23) {
            return $this->calculateBaseOnPph23();
        } elseif($this->taxNumber == 26) {
            return $this->calculateBaseOnPph26();
        }
    }

    // ------------------------------------------------------------------------

    /**
     * PayrollCalculator::calculateBaseOnPph21
     *
     * @return \O2System\Spl\DataStructures\SplArrayObject
     */
    private function calculateBaseOnPph21()
    {
        // Gaji + Penghasilan teratur
        $this->result->earnings->base = ($this->employee->earnings->base / $this->provisions->company->numOfWorkingDays) * $this->employee->presences->workDays;
        $this->result->earnings->fixedAllowance = $this->employee->earnings->fixedAllowance;

        // Penghasilan bruto bulanan merupakan gaji pokok ditambah tunjangan tetap
        $this->result->earnings->gross = $this->result->earnings->base + $this->employee->earnings->fixedAllowance;

        if($this->employee->calculateHolidayAllowance > 0) {
            $this->result->earnings->holidayAllowance = $this->employee->calculateHolidayAllowance * $this->result->earnings->gross;
        }

        // Penghasilan tidak teratur
        if($this->provisions->company->calculateOvertime === true) {
            //  Berdasarkan Kepmenakertrans No. 102/MEN/VI/2004
            $this->result->earnings->overtime = $this->employee->earnings->overtime;
            // if($this->employee->presences->overtime > 1) {
            //     $overtime1stHours = 1 * 1.5 * 1/173 * $this->result->earnings->gross;
            //     $overtime2ndHours = ($this->employee->presences->overtime - 1) * 2 * 1/173 * $this->result->earnings->gross;
            //     $this->result->earnings->overtime = $overtime1stHours + $overtime2ndHours;
            // } else {
            //     $this->result->earnings->overtime = $this->employee->presences->overtime * 1.5 * 1/173 * $this->result->earnings->gross;
            // }

            $this->result->earnings->overtime = floor($this->result->earnings->overtime);

            // Lembur ditambahkan sebagai pendapatan bruto bulanan
            $this->result->earnings->gross = $this->result->earnings->gross + $this->result->earnings->overtime;
        }

        $this->result->earnings->annualy->gross = $this->result->earnings->gross * 12;
        
        if($this->employee->permanentStatus === false) {
            $this->employee->allowances->BPJSKesehatan = 0;
            $this->employee->deductions->BPJSKesehatan = 0;
            
            $this->employee->allowances->JKK = 0;
            $this->employee->allowances->JKM = 0;
            
            $this->employee->allowances->JHT = 0;
            $this->employee->deductions->JHT = 0;

            $this->employee->allowances->JIP = 0;
            $this->employee->deductions->JIP = 0;

            // Set result allowances, bonus, deductions
            $this->result->offsetSet('allowances', $this->employee->allowances);
            $this->result->offsetSet('bonus', $this->employee->bonus);
            $this->result->offsetSet('deductions', $this->employee->deductions);

            // Pendapatan bersih
            $this->result->earnings->nett = $this->result->earnings->gross + $this->result->allowances->getSum() - $this->result->deductions->getSum();
            $this->result->earnings->annualy->nett = $this->result->earnings->nett * 12;
            
            $this->result->offsetSet('taxable', (new Pph21($this))->result);

            // Pengurangan Penalty
            $this->employee->deductions->offsetSet('penalty', new SplArrayObject([
                'late' => $this->employee->presences->latetime * $this->provisions->company->latetimePenalty,
                'absent' => $this->employee->presences->absentDays * $this->provisions->company->absentPenalty
            ]));

            // Tunjangan Hari Raya
            if($this->employee->earnings->holidayAllowance > 0) {
                $this->result->allowances->offsetSet('holiday', $this->employee->earnings->holidayAllowance);
            }

            $this->result->takeHomePay = $this->result->earnings->nett + $this->employee->earnings->holidayAllowance + $this->employee->bonus->getSum() - $this->employee->deductions->penalty->getSum();
            $this->result->allowances->offsetSet('positionTax', 0);
            $this->result->allowances->offsetSet('pph21Tax', 0);
        } else {
            if ($this->provisions->company->calculateBPJSKesehatan === true) {
                // Calculate BPJS Kesehatan Allowance & Deduction
                if ($this->employee->allowances->count()) {
                    $jumlah = array();
                    foreach ($this->employee->allowances as $key => $value) {
                        $jumlah[] = $value;
                    }
                    if ($this->result->earnings->base > 8000000) {
                        $this->employee->allowances->BPJSKesehatan = 8000000 * (4 / 100);
                    }else{
                        $this->employee->allowances->BPJSKesehatan = ($this->result->earnings->gross + array_sum($jumlah)) * (4 / 100);
                    }
                    
                    $this->employee->deductions->BPJSKesehatan = ($this->result->earnings->gross + array_sum($jumlah)) * (1 / 100);
                } else {
                    if ($this->result->earnings->base > 8000000) {
                        $this->employee->allowances->BPJSKesehatan = 8000000 * (4 / 100);
                        $this->employee->deductions->BPJSKesehatan = 8000000 * (1 / 100);
                    }else{
                        $this->employee->allowances->BPJSKesehatan = $this->result->earnings->base * (4 / 100);
                        $this->employee->deductions->BPJSKesehatan = $this->result->earnings->base * (1 / 100);
                    }
                }

                // Maximum number of dependents family is 5
                if ($this->employee->numOfDependentsFamily > 5) {
                    $this->employee->deductions->BPJSKesehatan = $this->employee->deductions->BPJSKesehatan + ($this->employee->deductions->BPJSKesehatan * ($this->employee->numOfDependentsFamily - 5));
                }
            }

            // Calculate BPJS Ketenagakerjaan
            if($this->provisions->company->calculateBPJSKetenagakerjaan === true) {
                if ($this->result->earnings->gross < $this->provisions->state->highestWage) {
                    $this->employee->allowances->JKK = round(($this->result->earnings->base+$this->result->earnings->fixedAllowance) * ($this->provisions->state->getJKKRiskGradePercentage($this->provisions->company->riskGrade) / 100));

                    /**
                     * Perhitungan JKM
                     *
                     * Iuran jaminan kematian (JKM) sebesar 0,30% dari upah sebulan.
                     * Ditanggung sepenuhnya oleh perusahaan.
                     */
                    $this->employee->allowances->JKM = ($this->result->earnings->base+$this->result->earnings->fixedAllowance) * (0.30 / 100);

                    /**
                     * Perhitungan JHT
                     *
                     * Iuran jaminan hari tua (JHT) sebesar 5,7% dari upah sebulan,
                     * dengan ketentuan 3,7% ditanggung oleh pemberi kerja dan 2% ditanggung oleh pekerja.
                     */
                    // $this->employee->allowances->JHT = $this->result->earnings->gross * (3.7 / 100);
                    $this->employee->deductions->JHT = ($this->result->earnings->base+$this->result->earnings->fixedAllowance) * (2 / 100);

                    /**
                     * Perhitungan JP
                     *
                     * Iuran jaminan pensiun (JP) sebesar 3% dari upah sebulan,
                     * dengan ketentuan 2% ditanggung oleh pemberi kerja dan 1% ditanggung oleh pekerja.
                     */
                    // $this->employee->allowances->JIP = $this->result->earnings->gross * (2 / 100);
                    $this->employee->deductions->JIP = ($this->result->earnings->base+$this->result->earnings->fixedAllowance) * (1 / 100);

                } elseif ($this->result->earnings->gross >= $this->provisions->state->provinceMinimumWage && $this->result->earnings->gross >= $this->provisions->state->highestWage) {
                    $this->employee->allowances->JKK = $this->provisions->state->highestWage * $this->provisions->state->getJKKRiskGradePercentage($this->provisions->company->riskGrade);

                    /**
                     * Perhitungan JKM
                     *
                     * Iuran jaminan kematian (JKM) sebesar 0,30% dari upah sebulan.
                     * Ditanggung sepenuhnya oleh perusahaan.
                     */
                    $this->employee->allowances->JKM = $this->provisions->state->highestWage * (0.30 / 100);

                    /**
                     * Perhitungan JHT
                     *
                     * Iuran jaminan hari tua (JHT) sebesar 5,7% dari upah sebulan,
                     * dengan ketentuan 3,7% ditanggung oleh pemberi kerja dan 2% ditanggung oleh pekerja.
                     *
                     * Dihitung dari batas nilai upah bulanan tertinggi, karena total income melebihi UMP.
                     */
                    $this->employee->allowances->JHT = $this->provisions->state->highestWage * (3.7 / 100);
                    $this->employee->deductions->JHT = $this->provisions->state->highestWage * (2 / 100);

                    /**
                     * Perhitungan JIP
                     *
                     * Iuran jaminan pensiun (JIP) sebesar 3% dari upah sebulan,
                     * dengan ketentuan 2% ditanggung oleh pemberi kerja dan 1% ditanggung oleh pekerja.
                     *
                     * Maximum Perhitungan total income jaminan pensiun adalah 7.000.000
                     */
                    $this->employee->allowances->JIP = 7000000 * (2 / 100);
                    $this->employee->deductions->JIP = 7000000 * (1 / 100);
                }
            }

            // Set result allowances, bonus, deductions
            $this->result->offsetSet('allowances', $this->employee->allowances);
            $this->result->offsetSet('bonus', $this->employee->bonus);
            $this->result->offsetSet('deductions', $this->employee->deductions);

            // Pendapatan bersih
            $pendapatanBersih = array();
            foreach ($this->result->allowances as $key => $value) {
                $pendapatanBersih[] = $value;
            }

            $pendapatanBersih2 = array();
            foreach ($this->result->deductions as $key => $value) {
                $pendapatanBersih2[] = $value;
            }
            $this->result->earnings->nett = $this->result->earnings->gross + array_sum($pendapatanBersih) - array_sum($pendapatanBersih2);
            $this->result->earnings->annualy->nett = $this->result->earnings->nett * 12;

            $monthlyPositionTax = 0;
            if ($this->result->earnings->gross > $this->provisions->state->provinceMinimumWage) {

                /**
                 * According to Undang-Undang Direktur Jenderal Pajak Nomor PER-32/PJ/2015 Pasal 21 ayat 3
                 * Position Deduction is 5% from Annual Gross Income
                 */
                $monthlyPositionTax = $this->result->earnings->nett * (5 / 100);

                /**
                 * Maximum Position Deduction in Indonesia is 500000 / month
                 * or 6000000 / year
                 */
                if ($monthlyPositionTax >= 500000) {
                    $monthlyPositionTax = 500000;
                }
            }

            $this->result->earnings->nett = $this->result->earnings->nett - $monthlyPositionTax;
            $this->result->earnings->annualy->nett = $this->result->earnings->nett * 12;

            $this->result->offsetSet('taxable', (new Pph21($this))->calculate());

            // Pengurangan Penalty
            $this->employee->deductions->offsetSet('penalty', new SplArrayObject([
                'late' => $this->employee->presences->latetime * $this->provisions->company->latetimePenalty,
                'absent' => $this->employee->presences->absentDays * $this->provisions->company->absentPenalty
            ]));

            // Tunjangan Hari Raya
            if($this->employee->earnings->holidayAllowance > 0) {
                $this->result->allowances->offsetSet('holiday', $this->employee->earnings->holidayAllowance);
            }
            switch ($this->method) {
                // Pajak ditanggung oleh perusahaan
                case self::NETT_CALCULATION:
                    $bonus = array();
                    foreach ($this->employee->bonus as $key => $value) {
                        $bonus[] = $value;
                    }
                    $penalty = array();
                    foreach ($this->employee->deductions->penalty as $key => $value) {
                        $penalty[] = $value;
                    }
                    $pengurangan = array();
                    foreach ($this->result->deductions as $key => $value) {
                        $pengurangan[] = $value;
                    }
                    $this->result->takeHomePay = $this->result->earnings->base+$this->result->earnings->fixedAllowance+$this->result->earnings->overtime-array_sum($pengurangan);

                    $this->result->allowances->offsetSet('positionTax', $monthlyPositionTax);
                    $this->result->allowances->offsetSet('pph21Tax', $this->result->taxable->pph21count);
                    break;
                // Pajak ditanggung oleh karyawan
                case self::GROSS_CALCULATION:
                    $bonus = array();
                    foreach ($this->employee->bonus as $key => $value) {
                        $bonus[] = $value;
                    }
                    $penalty = array();
                    foreach ($this->employee->deductions->penalty as $key => $value) {
                        $penalty[] = $value;
                    }
                    $pengurangan = array();
                    foreach ($this->result->deductions as $key => $value) {
                        $pengurangan[] = $value;
                    }
                    $this->result->takeHomePay = $this->result->earnings->base+$this->result->earnings->fixedAllowance+$this->result->earnings->overtime-array_sum($pengurangan)-$this->result->taxable->pph21count;

                    $this->result->deductions->offsetSet('positionTax', $monthlyPositionTax);
                    $this->result->deductions->offsetSet('pph21Tax', $this->result->taxable->pph21count);
                    break;
                // Pajak ditanggung oleh perusahaan sebagai tunjangan pajak.
                case self::GROSS_UP_CALCULATION:
                    $bonus = array();
                    foreach ($this->employee->bonus as $key => $value) {
                        $bonus[] = $value;
                    }
                    $penalty = array();
                    foreach ($this->employee->deductions->penalty as $key => $value) {
                        $penalty[] = $value;
                    }
                    $pengurangan = array();
                    foreach ($this->result->deductions as $key => $value) {
                        $pengurangan[] = $value;
                    }
                    $this->result->takeHomePay = $this->result->earnings->base+$this->result->earnings->fixedAllowance+$this->result->earnings->overtime-array_sum($pengurangan);

                    $this->result->allowances->offsetSet('positionTax', $monthlyPositionTax);
                    $this->result->allowances->offsetSet('pph21Tax', $this->result->taxable->pph21count);
                    $this->result->deductions->offsetSet('pph21Tax', $this->result->taxable->pph21count);
                    break;
            }
        }

        return $this->result;
    }

    // ------------------------------------------------------------------------

    /**
     * PayrollCalculator::calculateBaseOnPph23
     *
     * @return \O2System\Spl\DataStructures\SplArrayObject
     */
    private function calculateBaseOnPph23()
    {
        // Gaji + Penghasilan teratur
        $this->result->earnings->base = $this->employee->earnings->base ;
        $this->result->earnings->fixedAllowance = $this->employee->earnings->fixedAllowance;

        // Penghasilan bruto bulanan merupakan gaji pokok ditambah tunjangan tetap
        $this->result->earnings->gross = $this->result->earnings->base + $this->employee->earnings->fixedAllowance;

        if($this->employee->calculateHolidayAllowance > 0) {
            $this->result->earnings->holidayAllowance = $this->employee->calculateHolidayAllowance * $this->result->earnings->gross;
        }

        // Set result allowances, bonus, deductions
        $this->result->offsetSet('allowances', $this->employee->allowances);
        $this->result->offsetSet('bonus', $this->employee->bonus);
        $this->result->offsetSet('deductions', $this->employee->deductions);

        // Pendapatan bersih
        $this->result->earnings->nett = $this->result->earnings->gross + $this->result->allowances->getSum() - $this->result->deductions->getSum();
        $this->result->earnings->annualy->nett = $this->result->earnings->nett * 12;

        $this->result->offsetSet('taxable', (new Pph23($this))->calculate());

        switch ($this->method) {
            // Pajak ditanggung oleh perusahaan
            case self::NETT_CALCULATION:
                $this->result->takeHomePay = $this->result->earnings->nett + $this->employee->earnings->holidayAllowance + $this->employee->bonus->getSum();
                break;
            // Pajak ditanggung oleh karyawan
            case self::GROSS_CALCULATION:
                $this->result->takeHomePay = $this->result->earnings->nett + $this->employee->bonus->getSum()- $this->result->taxable->liability->amount;
                $this->result->deductions->offsetSet('pph23Tax', $this->result->taxable->liability->amount);
                break;
            // Pajak ditanggung oleh perusahaan sebagai tunjangan pajak.
            case self::GROSS_UP_CALCULATION:
                $this->result->takeHomePay = $this->result->earnings->nett + $this->employee->bonus->getSum();
                $this->result->allowances->offsetSet('pph23Tax', $this->result->taxable->liability->amount);
                break;
        }

        return $this->result;
    }

    // ------------------------------------------------------------------------

    /**
     * PayrollCalculator::calculateBaseOnPph26
     *
     * @return \O2System\Spl\DataStructures\SplArrayObject
     */
    private function calculateBaseOnPph26()
    {
        // Gaji + Penghasilan teratur
        $this->result->earnings->base = $this->employee->earnings->base ;
        $this->result->earnings->fixedAllowance = $this->employee->earnings->fixedAllowance;

        // Penghasilan bruto bulanan merupakan gaji pokok ditambah tunjangan tetap
        $this->result->earnings->gross = $this->result->earnings->base + $this->employee->earnings->fixedAllowance;

        if($this->employee->calculateHolidayAllowance > 0) {
            $this->result->earnings->holidayAllowance = $this->employee->calculateHolidayAllowance * $this->result->earnings->gross;
        }

        // Set result allowances, bonus, deductions
        $this->result->offsetSet('allowances', $this->employee->allowances);
        $this->result->offsetSet('bonus', $this->employee->bonus);
        $this->result->offsetSet('deductions', $this->employee->deductions);

        // Pendapatan bersih
        $this->result->earnings->nett = $this->result->earnings->gross + $this->result->allowances->getSum() - $this->result->deductions->getSum();
        $this->result->earnings->annualy->nett = $this->result->earnings->nett * 12;

        $this->result->offsetSet('taxable', (new Pph26($this))->calculate());

        switch ($this->method) {
            // Pajak ditanggung oleh perusahaan
            case self::NETT_CALCULATION:
                $this->result->takeHomePay = $this->result->earnings->nett + $this->employee->earnings->holidayAllowance + $this->employee->bonus->getSum();
                break;
            // Pajak ditanggung oleh karyawan
            case self::GROSS_CALCULATION:
                $this->result->takeHomePay = $this->result->earnings->nett + $this->employee->bonus->getSum()- $this->result->taxable->liability->amount;
                $this->result->deductions->offsetSet('pph26Tax', $this->result->taxable->liability->amount);
                break;
            // Pajak ditanggung oleh perusahaan sebagai tunjangan pajak.
            case self::GROSS_UP_CALCULATION:
                $this->result->takeHomePay = $this->result->earnings->nett + $this->employee->bonus->getSum();
                $this->result->allowances->offsetSet('pph26Tax', $this->result->taxable->liability->amount);
                break;
        }

        return $this->result;
    }
}
