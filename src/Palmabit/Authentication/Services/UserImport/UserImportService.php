<?php  namespace Palmabit\Authentication\Services\UserImport;
use Palmabit\Library\ImportExport\EloquentDbSaver;

/**
 * Class UserImportService
 *
 * @author jacopo beschi jacopo@jacopobeschi.com
 */
class UserImportService 
{
    public function importCsv(array $input)
    {
        $path = $input["file"]->getRealPath();

        // read data
        $csv_reader = new UserCsvFileReader();
        $csv_reader->open($path);
        $csv_reader->readElements();
        $csv_reader->istantiateObjects();
        $objects = $csv_reader->getObjectsIstantiated();

        // save file
        $saver = new EloquentDbSaver();
        $saver->save($objects);
    }
} 