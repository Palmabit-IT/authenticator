<?php  namespace Palmabit\Authentication\Services\UserImport;
use Palmabit\Library\ImportExport\CsvFileReader;
/**
 * Class DbCsvFileReader
 *
 * @author jacopo beschi j.beschi@palmabit.com
 */
class UserCsvFileReader extends CsvFileReader
{
   protected $istantiated_objects_class_name = '\Palmabit\Authentication\Models\UserDbImportSaver';
}