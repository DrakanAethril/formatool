<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


class TimeTableGenerator {

    private ?Trainings $training = null;

    private ?array $timeSlots = null;

    private ?Collection $topics = null;

    private ?array $timeSlotsLengthInfos = null;


    private ?array $topicsVolumePerTimeSlots = null;

    private ?array $weeklyVolumePerTimeSlots = null;

    public function __construct(Trainings $training = null) {
        $this->training = $training;
        if(!empty($training)) {
            foreach($this->training->getTimeSlots() as $timeSlot) {
                if($timeSlot->getTimeSlotsTypes()->getId() == TimeSlotsTypes::LESSONS_TYPE) {
                    $this->timeSlots[$timeSlot->getId()] = $timeSlot;
                }
            }
            //$this->timeSlots = $this->training->getTimeSlots();
            $this->topics = $this->training->getTrainings();
        }
    }

    public function generateTimeTable(bool $dryRun = false) {
        $this->timeSlotsLengthInfos = $this->generateTimeSlotsLength();
        $this->topicsVolumePerTimeSlots = $this->calculateTopicsPerWeekInfos();
        $this->weeklyVolumePerTimeSlots = $this->calculateWeeklyVolumePerWeekInfos();
        //dd( $this->topicsVolumePerTimeSlots);
        //dd($this->weeklyVolumePerTimeSlots);
        $this->generateRealTimeTable($dryRun);
    }


    public function generateTimeSlotsLength() : array {
        $res = [];
        foreach($this->getTimeSlots() as $timeSlot) {
            $totalDaysPeriod = 0;
            
            $firstDay = $timeSlot->getStartDate()->format('U');
            $lastDay = $timeSlot->getEndDate()->format('U');
            while ($lastDay > $firstDay) {
                $weekDay = date("w", $firstDay);
                if($weekDay > 0 && $weekDay <=5) {
                    $totalDaysPeriod++;
                }
                $firstDay += 24*60*60;
            }
            $res[$timeSlot->getId()] = [
                'totalDays' => $totalDaysPeriod,
                'nbFullWeek' => floor($totalDaysPeriod/5),
                'remainingDays' => $totalDaysPeriod%5
            ];
            
        }
        return $res;
    }

    public function calculateTopicsPerWeekInfos() : array { 
        $res = [];
        $totalHours = 0;
        
        foreach($this->getTopics() as $topic) {
            $totalHours += $topic->getTotalVolume();
            if(!empty($topic->getTotalVolume())){
                $allowedTimeSlots = array_keys($this->timeSlotsLengthInfos);
                
                // Is this topic restricted to specific slots
                if(count($topic->getTimeslots())>0) {
                    $allowedTimeSlots = [];
                    foreach($topic->getTimeslots() as $slot) {
                        $allowedTimeSlots[] = $slot->getId();
                    }
                }
                if(!empty($allowedTimeSlots)) {
                    $res[$topic->getId()] = $this->splitTopicsHoursAcrossWeeks($allowedTimeSlots, $topic);
                }
                
            }
        }

        return $res;
    }

    public function getTimeSlotLength(int $timeSlotId) : array {
        return $this->timeSlotsLengthInfos[$timeSlotId] ?? [];
    }

    public function getAvailableWeeksForTimeSlotsList(array $timeSlots) : int {
        $res = 0;
        foreach($timeSlots as $timeSlot) {
            $res += $this->getTimeSlotLength($timeSlot)['nbFullWeek'];
        }
        return $res;
    }

    public function splitTopicsHoursAcrossWeeks(array $timeSlots, TopicsTrainings $topic) : array {
        $res = [];
        
        //first round : split total hours numbers per total weeks
        $totalWeeks = $this->getAvailableWeeksForTimeSlotsList($timeSlots);
        /*foreach(TimeSlotsTypes::getSupportedLessonsType() as $lessonType) {
            $getter = 'get'.$lessonType;*/
        $typeVolume = $topic->getTotalVolume();
        if(!empty($typeVolume) && $typeVolume > $totalWeeks) {
            // Let's add some hours to all timeSlots for the moment
            foreach($timeSlots as $timeSlot) {
                $res['regular']['totalVolume'][$timeSlot] = floor($typeVolume/$totalWeeks);
            }
            // If we have some remaining time let see what we can do about it
            $remainingTime = $typeVolume%$totalWeeks;
            $res['remaining']['totalVolume'] = $remainingTime;
        } else {
            $res['remaining']['totalVolume'] = $typeVolume;
        }
        if($res['remaining']['totalVolume'] > 0 && count($timeSlots) > 1) { // we need at least 2 time slots to have a chance to do sthing
            //let's try to add some regular hours to some time slots
            $filledRemainingTime = $this->getBestTimeSlotToPutRegularSpareHours($timeSlots, $res['remaining']['totalVolume']);
            if(!empty($filledRemainingTime['regular']) ) {
                foreach($filledRemainingTime['regular'] as $ts => $nbHours) {
                    if(!empty($res['regular']['totalVolume'][$ts])) {
                        $res['regular']['totalVolume'][$ts] += $nbHours;
                    } else {
                        $res['regular']['totalVolume'][$ts] = $nbHours;
                    }
                    //$res['regular'][$lessonType][$ts] += $nbHours;
                }
                $res['remaining']['totalVolume'] = $filledRemainingTime['remaining'];
            }
        }
        /*}*/
        
        return $res;
    }

    public function getBestTimeSlotToPutRegularSpareHours(array $timeSlots, int $remainingTime) : array {
        $res = [];
        $tsList = [];
        // get available timeslots ordered by numbers of weeks desc
        foreach($timeSlots as $timeSlot) {
            $tsList[$timeSlot] = $this->getTimeSlotLength($timeSlot);
        }
        uasort($tsList, function($a, $b) { return $b['nbFullWeek'] - $a['nbFullWeek']; });
        // check the highest value with less weeks than hours.
        foreach($tsList as $timeSlot => $timeSlotInfo) {
            if($timeSlotInfo['nbFullWeek'] <= $remainingTime) {
                $res['regular'][$timeSlot] = floor($remainingTime/$timeSlotInfo['nbFullWeek']);
                $remainingTime = $remainingTime%$timeSlotInfo['nbFullWeek'];
            }
        }
        $res['remaining'] = $remainingTime;

        return $res;
    }

    public function calculateWeeklyVolumePerWeekInfos() : array {
        $res = [];
        $remainingHours = 0;
        if(!empty($this->topicsVolumePerTimeSlots)) {
            foreach($this->topicsVolumePerTimeSlots as $topicId => $regularData) {
                if(!empty($regularData['regular'])) {
                    foreach($regularData['regular'] as $lessonType) {
                        foreach($lessonType as $timeSlotId => $volume) {
                            if(!empty($res[$timeSlotId])) {
                                $res[$timeSlotId] += $volume;
                            } else {
                                $res[$timeSlotId] = $volume;
                            }
                        }
                    }
                }
                if(!empty($regularData['remaining'])) {
                    foreach($regularData['remaining'] as $lessonType => $volumeRemaining) {
                        //dd($regularData['remaining']);
                        $remainingHours += $volumeRemaining;
                    }
                }
            }
        }
        $res['totalRemainingHours'] = $remainingHours;
        return $res;
    }
    /**
     * Getter for property timeslots.
     *
     * @return Collection<int, TimeSlots>
     */
    public function getTimeSlots() :  array {
        return $this->timeSlots;
    }

    public function generateRealTimeTable($dryRun) : array {
        $planning = [];
        if(!empty($this->topicsVolumePerTimeSlots)) {
            foreach($this->getTimeSlots() as $timeSlotId => $timeSlot) {
                $sessionsToCreate = [];
                foreach($this->topicsVolumePerTimeSlots as $topicId => $regularData) {
                    $maxLength = 2;
                    $topicInfos = $this->getTopic($topicId);
                    if(!empty($topicInfos)) {
                        $maxLength = empty($topicInfos->getMaxSessionLength()) ? 2 : $topicInfos->getMaxSessionLength();
                    }
                    if(!empty($regularData['regular']['totalVolume']) && 
                        !empty($regularData['regular']['totalVolume'][$timeSlotId]) && 
                        !empty($regularData['regular']['totalVolume'][$timeSlotId])) 
                    {
                        if($regularData['regular']['totalVolume'][$timeSlotId] <= $maxLength) {
                            $sessionsToCreate[] = [
                                'topic' => $topicId,
                                'duration' => $regularData['regular']['totalVolume'][$timeSlotId]
                            ];
                        } else {
                            while($regularData['regular']['totalVolume'][$timeSlotId] >= $maxLength) {
                                $sessionsToCreate[] = [
                                    'topic' => $topicId,
                                    'duration' => $maxLength
                                ];
                                $regularData['regular']['totalVolume'][$timeSlotId] -= $maxLength;
                            }
                            if(!empty($regularData['regular']['totalVolume'][$timeSlotId])) {
                                $sessionsToCreate[] = [
                                    'topic' => $topicId,
                                    'duration' => $regularData['regular']['totalVolume'][$timeSlotId]
                                ];
                            }
                        }
                        
                    }
                   
                }
                if(!empty($sessionsToCreate)) {
                    $planning[$timeSlotId] = $this->convertSessionsToWeeklyPlanning($sessionsToCreate);
                }
            }
            if(1==1) { //!$dryRun
                $this->saveRealTimeTable($planning);
            }
        }
        return $planning;
    }

    public function convertSessionsToWeeklyPlanning(array $sessionsToCreate) : array {
        $res = [];
        $availablePeriods = $this->getAvailablePeriodsForTraining();
        shuffle($sessionsToCreate);
       foreach($sessionsToCreate as $kSTC => $sessionInfos) {
            $noSlots = true;
            foreach($availablePeriods as $kAP => $periodData) {
                if($periodData['volume'] >= $sessionInfos['duration']) {
                    $availablePeriods[$kAP]['volume'] -= $sessionInfos['duration'];
                    $availablePeriods[$kAP]['sessions'][] = $sessionInfos;
                    $noSlots = false;
                    break;;
                } 
            }
            if($noSlots) dd('Unable to generate timetable');
        }
        return $availablePeriods;
    }

    public function getAvailablePeriodsForTraining() : array {
        return [
            '1-am'=> ['volume' => 4, 'sessions' => []],
            '1-pm'=> ['volume' => 4, 'sessions' => []],
            '2-am'=> ['volume' => 4, 'sessions' => []],
            '2-pm'=> ['volume' => 4, 'sessions' => []],
            '3-am'=> ['volume' => 4, 'sessions' => []],
            '4-am'=> ['volume' => 4, 'sessions' => []],
            '4-pm'=> ['volume' => 4, 'sessions' => []],
            '5-am'=> ['volume' => 4, 'sessions' => []],
            '5-pm'=> ['volume' => 4, 'sessions' => []],
            '3-pm'=> ['volume' => 4, 'sessions' => []], 
        ];
    }

    public function saveRealTimeTable(array $planning) : bool {
        if(!empty($this->getTimeSlots())) {
            foreach($this->getTimeSlots() as $timeSlotId => $timeSlot) {
                if(!empty($planning[$timeSlotId])) {
                    $firstDay = $timeSlot->getStartDate()->format('U');
                    $lastDay = $timeSlot->getEndDate()->format('U');
                    while ($lastDay > $firstDay) {
                        $weekDay = date("w", $firstDay);
                        if($weekDay > 0 && $weekDay <=5) {
                            // Save the morning

                            // Save the afternoon
                        }
                        $firstDay += 24*60*60;
                    }
                }
            }
        }
        return true;
    }

    /**
     * Getter for property topics.
     *
     * @return Collection<int, TopicsTrainings>
     */
    public function getTopics() :  Collection {
        return $this->topics;
    }

    public function getTopic(int $topicId) : ?TopicsTrainings {
        $topics = $this->getTopics();
        foreach($topics as $topic) {
            if($topic->getId() == $topicId) return $topic;
        }
        return null;
    }
}