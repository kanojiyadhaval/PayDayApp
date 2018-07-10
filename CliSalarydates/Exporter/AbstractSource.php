<?php

namespace CliSalarydates\Exporter;

/**
 * Salary source abstract class
 */
abstract class AbstractSource
{
    /**
     * Number of months for salary
     */
    protected $_forMonths = 12;
    
    /**
     * Monthly bonus date
     */
    protected $_monthlyBonusDate = 15;
    
    /**
     * exclude days for salary and bonus
     */
    protected $_excludeDays = [
        'Sun',
        'Sat'
    ];
    
    /**
     * next salary day signature, if salary day within exclude day 
     */
    protected $_nextSalaryDateSign = '-1 day';
    
    /**
     * next bonus day signature, if bonus day within exclude day 
     */
    protected $_nextBonusDateSign = 'next wednesday';
    
    /**
     * @var array  
     */
    protected $_monthsSalaryDates = [];
    
    /**
     * @var array  
     */
    protected $_monthsBonusDates = [];
    
    /**
     * Modified date
     * @var date   
     */
    protected $_modifiedDate;
    
    public function __construct()
    {
        $this->_monthsSalaryDates = $this->getSalaryDates();
    }
    
    /**
     * generate data based on inputs
     * @return array
     */
    protected function getSalaryDates()
    {
        $salaryDatesArray = array();
        for ($i = 1; $i <= $this->_forMonths; $i++) {
            $timeStamp = strtotime(date( 'Y-m-01' )." +$i months");
            $this->validateDate(
                $this->getLastDateOfMonth($timeStamp),
                $this->_nextSalaryDateSign
            );            
            $salaryDatesArray[$this->getMonth($timeStamp)] = $this->_modifiedDate;
            
            $this->validateDate(
                $this->getBonusDateOfMonth($timeStamp),
                $this->_nextBonusDateSign
            );
            $this->_monthsBonusDates[$this->getMonth($timeStamp)] = $this->_modifiedDate;                    
        }
        return $salaryDatesArray;
    }
    
    /**
     * get last date of month
     * @param string $timeStamp
     * @return string
     */
    public function getLastDateOfMonth($timeStamp)
    {
        return date("Y-m-t", $timeStamp);
    }
    
    /**
     * get day of current date
     * @param string $date
     * @return string
     */
    public function getDay($date)
    {
        return date("D", strtotime($date));
    }
    
    /**
     * get month
     * @param string $timeStamp
     * @return string
     */
    public function getMonth($timeStamp)
    {
        return date("M Y", $timeStamp);
    }
    
    /**
     * validate current date if date has exclude days
     * @param string $date
     * @param string $modifier
     */
    protected function validateDate($date, $modifier)
    {
        $salaryDay = $this->getDay($date);
        if (array_search($salaryDay, $this->_excludeDays)===false) {
            $this->_modifiedDate = $date;
        } else {
            $modifiedDate = date('Y-m-d', strtotime($modifier, strtotime($date)));
            $this->validateDate($modifiedDate, $modifier);
        }
    }
    
    /**
     * get bonus date of the month
     * @param string $timeStamp
     * @return string
     */
    public function getBonusDateOfMonth($timeStamp)
    {
        $bonusDate = $this->_monthlyBonusDate - 1;
        return date('Y-m-d',strtotime(date('Y-m-d', $timeStamp)." +{$bonusDate} day"));
    } 

    /**
     * Export data
     *
     * @abstract
     */
    abstract protected function _exportData();
}
