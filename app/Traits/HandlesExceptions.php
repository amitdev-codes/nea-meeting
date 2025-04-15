<?php

namespace App\Traits;

use Exception;
use Illuminate\Database\QueryException;

trait HandlesExceptions
{
    public function handleExceptions(callable $callback, string $successRoute)
    {
        try {
            return $callback();
        } catch (QueryException $e) {
            return view('errors/503', [
                'error' => 'Database error: '.$e->getMessage(),
            ]);
        } catch (Exception $e) {
            return view('errors/503', [
                'error' => 'An unexpected error occurred: '.$e->getMessage(),
            ]);
        }
    }
}
