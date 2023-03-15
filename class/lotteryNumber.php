<?php
class LotteryNumber{

    public $Id;
    public $LotteryNumber;
    public $LotterySerialNumber;
    public $WinType;
    public $WinDate;
    public $WinTime;
    public $WinDayName;
    public $SlotId;
    public $Name;

    function __construct($Id, $LotteryNumber, $LotterySerialNumber, $WinType, $WinDate, $WinTime, $WinDayName, $SlotId, $Name)
    {
        $this->Id = $Id;
        $this->LotteryNumber = $LotteryNumber;
        $this->LotterySerialNumber = $LotterySerialNumber;
        $this->WinType = $WinType;
        $this->WinDate = $WinDate;
        $this->WinTime = $WinTime;
        $this->WinDayName = $WinDayName;
        $this->SlotId = $SlotId;
        $this->Name = $Name;
        
    }

}