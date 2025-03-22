<?php 
namespace Src\Interface;

// Added interface to make the DataImporter dependency interchangeable
interface DataImporter
{
    public static function importData():array;
}