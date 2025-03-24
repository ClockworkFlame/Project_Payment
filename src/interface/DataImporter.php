<?php 
namespace Src\Interface;

// Added interface to make the DataImporter dependency interchangeable
// Not the best idea to have an interface for static methods, but importer classes feel low-priority for me right now.
interface DataImporter
{
    public static function importData():array;

    public static function importUserData():array;

    public static function orderByUser(array $array):array;
}