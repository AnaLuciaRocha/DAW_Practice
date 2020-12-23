<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\DBAL\Driver\Connection;

class Bakery_modelController extends AbstractController
{
	private $connection;	
	public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }
}
