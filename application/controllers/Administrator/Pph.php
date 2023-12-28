<?php

class Pph extends CI_Controller 
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('PPH21/PayrollCalculator');
        $this->PayrollCalculator = new PayrollCalculator();
    }

    public function index()
    {
        // Calculation method
        $this->PayrollCalculator->method = PayrollCalculator::GROSS_CALCULATION;
        
        // Tax Number
        $this->PayrollCalculator->taxNumber = 21;

        // Set data karyawan
        $this->PayrollCalculator->employee->permanentStatus = true; // Tetap (true), Tidak Tetap (false), secara default sudah terisi nilai true.
        $this->PayrollCalculator->employee->maritalStatus = true; // Menikah (true), Tidak Menikah/Single (false), secara default sudah terisi nilai false.
        $this->PayrollCalculator->employee->hasNPWP = false; // Secara default sudah terisi nilai true. Jika tidak memiliki npwp akan dikenakan potongan tambahan 20%
        $this->PayrollCalculator->employee->numOfDependentsFamily = 6; // Jumlah tanggungan, max 5 jika lebih akan dikenakan tambahannya perorang sesuai ketentuan BPJS Kesehatan

        // Set data pendapatan karyawan
        $this->PayrollCalculator->employee->earnings->base =  5000000; // Besaran nilai gaji pokok/bulan
        $this->PayrollCalculator->employee->earnings->fixedAllowance = 500000; // Besaran nilai tunjangan tetap
        $this->PayrollCalculator->employee->earnings->overtime = 0; // Besaran nilai uang lembur/jam
        $this->PayrollCalculator->employee->calculateHolidayAllowance = 0; // jumlah bulan proporsional
        // NOTE: besaran nilai diatas bukan nilai hasil proses perhitungan absensi tetapi nilai default sebagai faktor perhitungan gaji.

        // Set data kehadiran karyawan
        $this->PayrollCalculator->employee->presences->workDays = 25; // jumlah hari masuk kerja
        $this->PayrollCalculator->employee->presences->overtime = 0; //  perhitungan jumlah lembur dalam satuan jam
        $this->PayrollCalculator->employee->presences->latetime = 0; //  perhitungan jumlah keterlambatan dalam satuan jam
        $this->PayrollCalculator->employee->presences->travelDays = 0; //  perhitungan jumlah hari kepergian dinas
        $this->PayrollCalculator->employee->presences->indisposedDays = 0; //  perhitungan jumlah hari sakit yang telah memiliki surat dokter
        $this->PayrollCalculator->employee->presences->absentDays = 0; //  perhitungan jumlah hari alpha

        // Set data tunjangan karyawan diluar tunjangan BPJS Kesehatan dan Ketenagakerjaan
        // $this->PayrollCalculator->employee->allowances->offsetSet('tunjanganMakan', 500000);
        // NOTE: Jumlah allowances tidak ada batasan

        // Set data potongan karyawan diluar potongan BPJS Kesehatan dan Ketenagakerjaan
        // $this->PayrollCalculator->employee->deductions->offsetSet('kasbon', 100000);
        // NOTE: Jumlah deductions tidak ada batasan

        // Set data bonus karyawan diluar tunjangan
        // $this->PayrollCalculator->employee->bonus->offsetSet('serviceCharge', 100000);
        // NOTE: Jumlah bonus tidak ada batasan

        // Set data ketentuan perusahaan
        $this->PayrollCalculator->provisions->company->numOfWorkingDays = 25; // Jumlah hari kerja dalam satu bulan
        $this->PayrollCalculator->provisions->company->calculateOvertime = true; // Apakah perusahaan memberlakukan perhitungan lembur
        $this->PayrollCalculator->provisions->company->calculateBPJSKesehatan = true; // Apakah perusahaan menyediakan BPJS Kesehatan / tidak untuk orang tersebut
        $this->PayrollCalculator->provisions->company->calculateBPJSKetenagakerjaan = true; // Apakah perusahaan menyediakan BPJS Ketenagakerjaan / tidak untuk orang tersebut
        $this->PayrollCalculator->provisions->company->riskGrade = 1; // Golongan resiko ketenagakerjaan, umumnya 2
        $this->PayrollCalculator->provisions->company->absentPenalty = 0; // Perhitungan nilai potongan gaji/hari sebagai penalty.
        $this->PayrollCalculator->provisions->company->latetimePenalty = 0; // Perhitungan nilai keterlambatan sebagai penalty.

        // Set data ketentuan negara
        $this->PayrollCalculator->provisions->state->provinceMinimumWage = 3500000; // Ketentuan UMP sesuai propinsi lokasi perusahaan
        // Mengambil hasil perhitungan
        echo json_encode($this->PayrollCalculator->getCalculation()); // Berupa SplArrayObject yang berisi seluruh data perhitungan gaji, lengkap dengan perhitungan BPJS dan PPh21
    }
}
