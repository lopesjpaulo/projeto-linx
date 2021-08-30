<?php 

namespace App\Providers;

use App\Repository\AccountRepositoryInterface;
use App\Repository\EloquentRepositoryInterface; 
use App\Repository\ClientRepositoryInterface;
use App\Repository\Eloquent\AccountRepository;
use App\Repository\Eloquent\ClientRepository; 
use App\Repository\Eloquent\BaseRepository;
use App\Repository\Eloquent\ExtractRepository;
use App\Repository\ExtractRepositoryInterface;
use Illuminate\Support\ServiceProvider; 

/** 
* Class RepositoryServiceProvider 
* @package App\Providers 
*/ 
class RepositoryServiceProvider extends ServiceProvider 
{ 
   /** 
    * Register services. 
    * 
    * @return void  
    */ 
   public function register() 
   { 
       $this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);
       $this->app->bind(ClientRepositoryInterface::class, ClientRepository::class);
       $this->app->bind(AccountRepositoryInterface::class, AccountRepository::class);
       $this->app->bind(ExtractRepositoryInterface::class, ExtractRepository::class);
   }
}