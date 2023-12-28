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

// ------------------------------------------------------------------------

/**
 * Class Employee
 * @package Steevenz\IndonesiaPayrollCalculator\DataStructures
 */

include_once("Employee/Presences.php");
include_once("Employee/Earnings.php");
include_once("Employee/Allowances.php");
include_once("Employee/Bonus.php");
include_once("Employee/Deductions.php");

class Employee
{
    /**
     * Employee::$permanentStatus
     *
     * @var bool
     */
    public $permanentStatus = true;
    
    /**
     * Employee::$maritalStatus
     *
     * @var bool
     */
    public $maritalStatus = false;

    /**
     * Employee::$hasNPWP
     *
     * @var bool
     */
    public $hasNPWP = true;

    /**
     * Employee::$numOfDependentsFamily
     *
     * @var int
     */
    public $numOfDependentsFamily = 0;

    /**
     * Employee::$presences
     *
     * @var \Steevenz\IndonesiaPayrollCalculator\DataStructures\Employee\Presences
     */
    public $presences;

    /**
     * Employee::$earnings
     *
     * @var \Steevenz\IndonesiaPayrollCalculator\DataStructures\Employee\Earnings
     */
    public $earnings;

    /**
     * Employee::$allowances
     *
     * @var \Steevenz\IndonesiaPayrollCalculator\DataStructures\Employee\Allowances
     */
    public $allowances;

    /**
     * Employee::$deductions
     *
     * @var \Steevenz\IndonesiaPayrollCalculator\DataStructures\Employee\Deductions
     */
    public $deductions;

    /**
     * Company::$calculateHolidayAllowance
     *
     * @var int
     */
    public $calculateHolidayAllowance = 0;

    /**
     * Employee::$bonus
     *
     * @var \Steevenz\IndonesiaPayrollCalculator\DataStructures\Employee\Bonus
     */
    public $bonus;

    // ------------------------------------------------------------------------

    /**
     * Employee::__construct
     */
    public function __construct()
    {
        $this->presences = new Presences();
        $this->earnings = new Earnings();
        $this->allowances = new Allowances();
        $this->deductions = new Deductions();
        $this->bonus = new Bonus();
    }
}