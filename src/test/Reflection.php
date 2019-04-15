<?php

namespace Work;

class People
{
    public function __construct($id = 1993, $name = 'maerhahaha')
    {
        $this->id = $id;
        $this->name = $name;
    }
}

class Student
{
    private $people;

    public function __construct(People $people)
    {
        $this->people = $people;
    }

    public function getObj()
    {
        return $this->people;
    }
}

class WA
{
    private $student = null;

    public function __construct(Student $student)
    {
        $this->student = $student;
    }

    public function getStudent()
    {
        return $this->student;
    }
}


class MyReflection
{
    private $reflectionClass = [];
    private $refDepentds= [];

    public function get($class, $params = [])
    {
        if (!isset($this->reflectionClass[$class])) {
            return $this->build($class, $params);
        }
    }

    public function build($class, $params)
    {
        list($depends, $reflection) = $this->getDepends($class);
        foreach ($params as $key => $param) {
            $depends[$key]  = $param;
        }
        if(!empty($depends)){
            $depends = $this->resoveDepend($depends, $reflection);
        }
        return $reflection->newInstanceArgs($depends);
    }

    public function getDepends($class){
        $refDepends = [];
        $refClass = new \ReflectionClass($class);
        $constructor = $refClass->getConstructor();
        $constructParams = $constructor->getParameters();
        if ($constructor) {
            foreach ($constructParams as $param) {
                if ($param->isDefaultValueAvailable()) {
                    $refDepends[$param->getName()] = $param->getDefaultValue();
                } else {
                    $c = $param->getClass();
                    $refDepends[$param->getName()] = RefCls::Init($c);
                }
            }
        }
        $this->reflectionClass[$class] = $refClass;
        $this->refDepentds[$class] = $refDepends;
        return [$refDepends, $refClass];
    }

    public function resoveDepend($depends,\ReflectionClass $reflection = null){
        foreach ($depends as $key => $param){
            if($param instanceof RefCls){
                if($param->id !== null){
                    $depends[$key] = $this->get($param->id->name);
                }elseif($reflection !== null){
                    $name = $reflection->getConstructor()->getParameters()[$key]->getName();
                    $class = $reflection->getName();
                    throw new \Exception("Missing required parameter \"$name\" when instantiating \"$class\".");
                }echo 123;'this is a ';
            }
        }
        return $depends;
    }
}

class RefCls {
    public $id = null;
    private function __construct($id)
    {
        $this->id = $id;
    }

    public static function Init($id = null){
        return new static($id);
    }
}
$myReflection = new MyReflection();
$student = new Student(new \Work\People(555, 'wangqiang'));
$class  = $myReflection->get(WA::class, ['student' => $student]);
var_dump($class);
