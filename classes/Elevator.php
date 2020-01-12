<?php

namespace Classes;

class Elevator
{
    private $flor = 1;
    private $direction = 'UP';
    private $to = 1;
    private $data = [];
    private $people = [];
    private $goToLevel = false;

    public function __construct($data)
    {
        $this->data = $data;
    }

    //sort array direction

    public function sortDirection()
    {
        $first = [];
        $second = [];
        foreach ($this->data as $item) {
            if ($this->direction == $item['direction'] && $this->helperDirection($item['flor'])) {
                if($item['flor'] == $this->to){
                    array_unshift($first,$item);
                    continue;
                }
                array_push($first, $item);
                continue;
            }
            array_push($second, $item);
        }
        if(count($first) == 1 && !empty($second)){
            $this->goToLevel = true;
        }
        $this->data = array_merge($first, $second);
    }

    public function next()
    {
        if (isset($this->people[$this->flor])) {
            echo ' Человек вышел на ' . $this->flor . PHP_EOL;
            unset($this->people[$this->flor]);
        }
        array_shift($this->data);
    }

    public function helperDirection($flor)
    {
        switch ($this->direction) {
            case 'UP':
                return $this->to >= $flor ? true : false;
            case 'DOWN':
                return $this->to <= $flor ? true : false;
        }
        return false;
    }

    //Просто пример передаю данные напрямую. Более сложное, в конструкторе подключаем сервис очереди и от туда берем данные.
    public function run()
    {
        $this->setToFlor();
        for ($i = 0; !empty($this->data) || !empty($this->people); $i++) {
            if (!empty($this->data)) {
                $this->setDirection();
                $this->sortDirection();
                $this->setToFlor();
                echo ' Этаж ' . $this->data[0]['flor']. PHP_EOL;
                echo ' Зашел человек нажал ' . $this->data[0]['to']. PHP_EOL;
                $this->addElevator($this->data[0]);
                $this->setFlor();
            } elseif(!empty($this->people)) {
                $this->flor = key($this->people);
            }
            $this->next();
        }
    }

    public function setFlor(){
        if($this->goToLevel){
            $this->flor = $this->data[0]['to'];
            $this->goToLevel = false;
            echo ' Этаж '.$this->flor. PHP_EOL;
        }else{
            $this->flor = $this->data[0]['flor'];
        }
    }

    public function setDirection()
    {
        $this->direction = $this->data[0]['direction'];
        echo ' Движение '.$this->direction. PHP_EOL;
    }

    public function setToFlor()
    {
        $this->to = $this->data[0]['to'];
    }

    public function addElevator($data)
    {
        if (isset($this->people[$data['to']])) {
            array_push($this->people[$data['to']], $data['direction']);
            return true;
        }
        $this->people[$data['to']] = [$data['direction']];
    }

}