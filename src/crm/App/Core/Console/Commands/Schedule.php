<?php

namespace App\Core\Console\Commands;

use App\Core\Console\Command;
use App\Core\Console\CommandInterface;
use App\Core\Helpers\Arr;
use Throwable;

final class Schedule extends Command
{
    private int|null $currentMin = null;
    
    private int|null $currentHour = null;
    
    private int|null $currentDay = null;
    
    private int|null $currentMonth = null;
    
    private int|null $currentWeekDay = null;
    
    
    protected function handle (): bool
    {
        $this->currentMin = (int) date('i');
        $this->currentHour = (int) date('H');
        $this->currentDay = (int) date('d');
        $this->currentMonth = (int) date('m');
        $this->currentWeekDay = (int) date('N');
        
        $commands = config('commands');
        
        foreach (config('schedule') as $scheduleTime => $scheduleData) {
            foreach ($commands as $commandName => $commandData) {
                try {
                    if (($command = $this->checkRunCommand($commandName, $commandData, $scheduleTime, $scheduleData)) && $command->run()) {
                        dump($command::class . ' successfully executed');
                    }
                } catch (Throwable $e) {
                    dump($e);
                    
                    return false;
                }
            }
        }
        
        $this->info('Schedule successfully executed ' . date('Y-m-d H:i:s') . PHP_EOL . PHP_EOL);
        
        return true;
    }
    
    private function checkRunCommand (string $commandName, array $commandData, string $scheduleTime, array $scheduleData): Command|false
    {
        $commandClass = Arr::get($commandData, 'class');
        $scheduleClass = Arr::get($scheduleData, 'class');
        $impl = class_implements($commandClass, true);
        
        if (
            $commandClass === $scheduleClass &&
            in_array(CommandInterface::class, $impl, false) &&
            ($class = new $commandClass(...$this->args)) &&
            $class instanceof Command &&
            $this->checkRunTime($scheduleTime)
        ) {
            return $class;
        }
        
        return false;
    }
    
    private function checkRunTime (string $scheduleTime): bool
    {
        [$minutes, $hours, $monthDays, $month, $weekDays] = explode(' ', $scheduleTime);
        $minutes = $this->setMinMax($minutes, 'minutes');
        $hours = $this->setMinMax($hours, 'hours');
        $monthDays = $this->setMinMax($monthDays, 'monthDays');
        $month = $this->setMinMax($month, 'mouths');
        $weekDays = $this->setMinMax($weekDays, 'weekDays');
        
        return ($minutes['min'] >= $this->currentMin && $minutes['max'] <= $this->currentMin &&
            $hours['min'] >= $this->currentHour && $hours['max'] <= $this->currentHour &&
            $monthDays['min'] >= $this->currentDay && $monthDays['max'] <= $this->currentDay &&
            $month['min'] >= $this->currentMonth && $month['max'] <= $this->currentMin &&
            $weekDays['min'] >= $this->currentWeekDay && $weekDays['max'] <= $this->currentWeekDay);
    }
    
    private function setMinMax (string $str, string $type): array
    {
        $params = match ($type) {
            'minutes' => ['i', 59, 'pattern' => '\b([0-5]?[0-9])\b'],
            'hours' => ['H', 23, 'pattern' => '(\d{1,2})'],
            'monthDays' => ['d', 31, 'pattern' => '(\d{1,2})'],
            'mouths' => ['m', 12, 'pattern' => '(\d{1,2})'],
            'weekDays' => ['N', 7, 'pattern' => '(\d{1,2})'],
        };
        
        if ($str === '*') {
            return [
                'min' => true,
                'max' => true,
            ];
        }
        
        if (preg_match('~^(?<min>' . $params['pattern'] . ')-(?<max>' . $params['pattern'] . ')$~', trim($str), $matches)) {
            return [
                'min' => (int) $matches['min'],
                'max' => (int) $matches['max'],
            ];
        }
        
        if (preg_match('~^(?<dividend>(.*?)\*)/(?<divider>\d+)$~', trim($str), $matches)) {
            $result = $this->getDivision($matches, $params);
            
            return [
                'min' => $result,
                'max' => $result,
            ];
        }
        
        return [
            'min' => false,
            'max' => false,
        ];
    }
    
    private function getDivision (array $matches, array $params): bool
    {
        if ($matches['divider'] === 0) {
            return false;
        }
        
        if ($matches['dividend'] === '*') {
            $matches['dividend'] = date($params[0]);
        }
        
        return (int) $matches['dividend'] % (int) $matches['divider'] === 0;
    }
    
    private function runCommand (Command $command): bool
    {
        return $command->run();
    }
}