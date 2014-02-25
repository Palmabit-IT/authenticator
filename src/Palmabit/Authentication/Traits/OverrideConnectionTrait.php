<?php  namespace Palmabit\Authentication\Traits;
/**
 * Trait OverrideConnectionTrait
 *
 * @author jacopo beschi j.beschi@palmabit.com
 */
use App;

trait OverrideConnectionTrait {
    /**
     * @override
     * @return \Illuminate\Database\Connection
     */
    public function getConnection()
    {
        return (App::environment() != 'testing') ? static::resolveConnection('authentication'): static::resolveConnection($this->connection);
    }
} 